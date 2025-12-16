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

        $query = Queue::where('user_id', $user->id);

        // 🔍 Filter per tanggal
        if ($request->filled('date')) {
            $query->whereDate('taken_at', $request->date);
        }

        // 🔍 Filter per bulan
        if ($request->filled('month')) {
            $query->whereMonth('taken_at', substr($request->month, 5, 2))
                  ->whereYear('taken_at', substr($request->month, 0, 4));
        }

        $queues = $query->latest()->get();

        return view('queue.history', compact('queues'));
    }
}
