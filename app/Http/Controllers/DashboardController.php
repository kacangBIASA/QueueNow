<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branchIds = $user->branches->pluck('id');

        // Antrean hari ini
        $todayCount = Queue::whereIn('branch_id', $branchIds)
            ->whereDate('created_at', today())
            ->count();

        // Antrean bulan ini
        $monthCount = Queue::whereIn('branch_id', $branchIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $dailyData = [];
        $monthlyData = [];

        if ($user->isPro()) {

            // 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);

                $dailyData[] = [
                    'date' => $date->format('d M'),
                    'total' => Queue::whereIn('branch_id', $branchIds)
                        ->whereDate('created_at', $date)
                        ->count()
                ];
            }

            // 12 bulan
            for ($m = 11; $m >= 0; $m--) {
                $month = Carbon::now()->subMonths($m);

                $monthlyData[] = [
                    'month' => $month->format('M Y'),
                    'total' => Queue::whereIn('branch_id', $branchIds)
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count()
                ];
            }
        }

        return view('dashboard.index', compact(
            'todayCount',
            'monthCount',
            'dailyData',
            'monthlyData'
        ));
    }
}
