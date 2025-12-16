<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicQueueController extends Controller
{
    public function show(string $public_code)
    {
        $branch = Branch::where('public_code', $public_code)->firstOrFail();
        $today = now()->toDateString();

        $currentlyCalled = Queue::where('branch_id', $branch->id)
            ->where('queue_date', $today)
            ->where('status', 'called')
            ->orderBy('called_at', 'desc')
            ->first();

        $waitingCount = Queue::where('branch_id', $branch->id)
            ->where('queue_date', $today)
            ->where('status', 'waiting')
            ->count();

        $lastTaken = Queue::where('branch_id', $branch->id)
            ->where('queue_date', $today)
            ->max('number');

        $myNumber = session("my_queue_number_{$branch->id}_{$today}");

        // QR (link ke halaman ini)
        $publicUrl = route('public.queue.show', $branch->public_code);

        return view('public.queue.show', compact(
            'branch','today','currentlyCalled','waitingCount','lastTaken','myNumber','publicUrl'
        ));
    }

    public function take(Request $request, string $public_code)
    {
        $request->validate([
            'source' => ['required', 'in:online,onsite'],
        ]);

        $branch = Branch::where('public_code', $public_code)->firstOrFail();
        $today = now()->toDateString();

        // Anti race-condition: transaction + retry bila unik konflik
        $attempts = 0;
        while ($attempts < 3) {
            $attempts++;

            try {
                $queue = DB::transaction(function () use ($branch, $today, $request) {
                    $max = Queue::where('branch_id', $branch->id)
                        ->where('queue_date', $today)
                        ->lockForUpdate()
                        ->max('number');

                    $next = $max ? $max + 1 : $branch->start_queue_number;

                    return Queue::create([
                        'branch_id' => $branch->id,
                        'queue_date' => $today,
                        'number' => $next,
                        'source' => $request->source,
                        'status' => 'waiting',
                        'taken_at' => now(),
                    ]);
                });

                session(["my_queue_number_{$branch->id}_{$today}" => $queue->number]);

                return redirect()
                    ->route('public.queue.show', $branch->public_code)
                    ->with('success', "Nomor antrean kamu: {$queue->number}");
            } catch (\Throwable $e) {
                // coba ulang kalau collision
                if ($attempts >= 3) throw $e;
            }
        }

        return back()->with('error', 'Gagal mengambil antrean. Coba lagi.');
    }
}
