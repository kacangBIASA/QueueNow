<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ambil semua cabang milik owner
        $branches = Branch::where('user_id', $user->id)->orderBy('id')->get();
        $branchIds = $branches->pluck('id');

        $today = now()->toDateString();
        $startMonth = now()->startOfMonth()->toDateString();
        $endMonth = now()->endOfMonth()->toDateString();

        // scorecards
        $todayCount = Queue::whereIn('branch_id', $branchIds)
            ->where('queue_date', $today)
            ->count();

        $monthCount = Queue::whereIn('branch_id', $branchIds)
            ->whereBetween('queue_date', [$startMonth, $endMonth])
            ->count();

        // chart (antrean per hari bulan ini) - untuk Pro
        $dailyCounts = Queue::whereIn('branch_id', $branchIds)
            ->whereBetween('queue_date', [$startMonth, $endMonth])
            ->selectRaw('queue_date as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $chartLabels = $dailyCounts->pluck('day')->map(fn($d) => (string)$d)->toArray();
        $chartValues = $dailyCounts->pluck('total')->toArray();

        return view('dashboard', compact(
            'branches',
            'todayCount',
            'monthCount',
            'chartLabels',
            'chartValues'
        ));
    }
}
