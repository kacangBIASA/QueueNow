<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    private function initMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createProPayment(Request $request)
    {
        $user = $request->user();

        if ($user->subscription_type === 'pro') {
            return redirect()->route('dashboard')->with('success', 'Akun kamu sudah Pro.');
        }

        $this->initMidtrans();

        $amount = config('midtrans.pro_price');
        $orderId = 'QN-PRO-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        $trx = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'gross_amount' => $amount,
            'status' => 'created',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'PRO',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => config('midtrans.pro_name'),
                ]
            ],
            // Optional: untuk memudahkan redirect setelah bayar (tidak semua channel pakai)
            'callbacks' => [
                'finish' => route('dashboard'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $trx->update(['snap_token' => $snapToken, 'status' => 'pending']);

        return redirect()->route('payment.pro.show', ['order_id' => $orderId]);
    }

    public function showProPayment(Request $request)
    {
        $user = $request->user();
        $orderId = $request->query('order_id');

        $trx = Transaction::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('payment.pro', [
            'trx' => $trx,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => (bool) config('midtrans.is_production'),
        ]);
    }

    public function midtransCallback(Request $request)
    {
        // Midtrans notification payload
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Verify signature
        $serverKey = config('midtrans.server_key');
        $computed = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if (!hash_equals($computed, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $trx = Transaction::where('order_id', $orderId)->first();
        if (!$trx) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? 'unknown';
        $paymentType = $payload['payment_type'] ?? null;

        // Update transaction
        $trx->update([
            'status' => $transactionStatus,
            'payment_type' => $paymentType,
            'payload' => $payload,
        ]);

        // Aktivasi PRO jika sukses
        // settlement / capture biasanya berarti paid
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $trx->user->update([
                'subscription_type' => 'pro',
                'pro_activated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
