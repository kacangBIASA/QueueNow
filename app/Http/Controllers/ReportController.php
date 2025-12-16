<?php

namespace App\Http\Controllers;

use App\Exports\QueueReportExport;
use App\Models\Branch;
use App\Models\Queue;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $branches = Branch::where('user_id', $user->id)->orderBy('id')->get();
        $branchId = (int) ($request->query('branch_id') ?? ($branches->first()->id ?? 0));

        $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : now()->startOfMonth();
        $to   = $request->query('to')   ? Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay();

        $branch = $branchId ? Branch::where('user_id', $user->id)->findOrFail($branchId) : null;

        $data = $branch ? $this->buildStats($branch->id, $from, $to) : null;

        return view('reports.index', compact('branches', 'branchId', 'branch', 'from', 'to', 'data'));
    }

    public function exportPdf(Request $request)
    {
        $user = $request->user();

        $branchId = (int) $request->query('branch_id');
        $from = Carbon::parse($request->query('from'))->startOfDay();
        $to   = Carbon::parse($request->query('to'))->endOfDay();

        $branch = Branch::where('user_id', $user->id)->findOrFail($branchId);
        $data = $this->buildStats($branch->id, $from, $to);

        $pdf = Pdf::loadView('reports.pdf', [
            'branch' => $branch,
            'from' => $from,
            'to' => $to,
            'data' => $data,
            'user' => $user,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("QueueNow_Report_{$branch->id}_{$from->format('Ymd')}_{$to->format('Ymd')}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $user = $request->user();

        $branchId = (int) $request->query('branch_id');
        $from = Carbon::parse($request->query('from'))->startOfDay();
        $to   = Carbon::parse($request->query('to'))->endOfDay();

        $branch = Branch::where('user_id', $user->id)->findOrFail($branchId);

        return Excel::download(
            new QueueReportExport($branch->id, $from, $to),
            "QueueNow_Report_{$branch->id}_{$from->format('Ymd')}_{$to->format('Ymd')}.xlsx"
        );
    }

    private function buildStats(int $branchId, Carbon $from, Carbon $to): array
    {
        $base = Queue::query()
            ->where('branch_id', $branchId)
            ->whereBetween('queue_date', [$from->toDateString(), $to->toDateString()]);

        $total = (clone $base)->count();

        // antrean per hari
        $perDay = (clone $base)
            ->selectRaw('queue_date as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // jam ramai (berdasarkan taken_at)
        $peak = (clone $base)
            ->whereNotNull('taken_at')
            ->selectRaw('HOUR(taken_at) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderByDesc('total')
            ->first();

        $peakHour = $peak ? str_pad((string) $peak->hour, 2, '0', STR_PAD_LEFT) . ':00' : '-';
        $peakCount = $peak->total ?? 0;

        // breakdown status
        $statusBreakdown = (clone $base)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'total' => $total,
            'perDay' => $perDay,
            'peakHour' => $peakHour,
            'peakCount' => $peakCount,
            'statusBreakdown' => $statusBreakdown,
        ];
    }
}
