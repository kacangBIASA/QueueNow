<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class QueueHistoryController extends Controller
{
    /**
     * Tampilkan riwayat antrean
     * Filter per hari / bulan
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Queue::with('branch')
            ->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // 🔍 Filter per tanggal
        if ($request->filled('date')) {
            $query->whereDate('queue_date', $request->date);
        }

        // 🔍 Filter per bulan (format: YYYY-MM)
        if ($request->filled('month')) {
            $query->whereYear('queue_date', substr($request->month, 0, 4))
                  ->whereMonth('queue_date', substr($request->month, 5, 2));
        }

        $queues = $query
            ->orderByDesc('queue_date')
            ->orderByDesc('number')
            ->get();

        return view('queue.history', compact('queues'));
    }
}
