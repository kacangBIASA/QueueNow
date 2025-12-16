<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerQueueController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        // ambil cabang milik owner
        $branches = Branch::where('user_id', $user->id)
            ->orderBy('id')
            ->get();

        // cabang aktif (default: pertama)
        $activeBranchId = (int) $request->query('branch_id', $branches->first()?->id ?? 0);
        $activeBranch = $branches->firstWhere('id', $activeBranchId) ?? $branches->first();

        $data = $activeBranch
            ? $this->getQueueData($activeBranch->id, $today)
            : null;

        return view('owner.queues.index', compact(
            'branches',
            'activeBranch',
            'today',
            'data'
        ));
    }



    public function callNext(Branch $branch)
    {
        $this->authorizeBranch($branch);
        $today = now()->toDateString();

        $called = false;

        DB::transaction(function () use ($branch, $today, &$called) {
            $next = Queue::where('branch_id', $branch->id)
                ->where('queue_date', $today)
                ->where('status', 'waiting')
                ->orderBy('number')
                ->lockForUpdate()
                ->first();

            if ($next) {
                $next->update([
                    'status' => 'called',
                    'called_at' => now(),
                ]);
                $called = true;
            }
        });

        return back()->with(
            $called ? 'success' : 'error',
            $called ? 'Memanggil antrean berikutnya.' : 'Belum ada antrean yang menunggu hari ini.'
        );
    }


    public function skip(Branch $branch, Queue $queue)
    {
        $this->authorizeBranch($branch);
        $this->authorizeQueue($branch, $queue);

        $queue->update([
            'status' => 'skipped',
            'finished_at' => null,
        ]);

        return back()->with('success', 'Antrean di-skip.');
    }

    public function finish(Branch $branch, Queue $queue)
    {
        $this->authorizeBranch($branch);
        $this->authorizeQueue($branch, $queue);

        $queue->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        return back()->with('success', 'Antrean selesai.');
    }

    public function resetDaily(Branch $branch)
    {
        $this->authorizeBranch($branch);

        $today = now()->toDateString();

        Queue::where('branch_id', $branch->id)
            ->where('queue_date', $today)
            ->delete();

        return back()->with('success', 'Antrean hari ini berhasil di-reset.');
    }

    private function getQueueData(int $branchId, string $today): array
    {
        $called = Queue::where('branch_id', $branchId)
            ->where('queue_date', $today)
            ->where('status', 'called')
            ->orderByDesc('called_at')
            ->first();

        $waiting = Queue::where('branch_id', $branchId)
            ->where('queue_date', $today)
            ->where('status', 'waiting')
            ->orderBy('number')
            ->get();

        $finishedCount = Queue::where('branch_id', $branchId)
            ->where('queue_date', $today)
            ->where('status', 'finished')
            ->count();

        $totalToday = Queue::where('branch_id', $branchId)
            ->where('queue_date', $today)
            ->count();

        return compact('called', 'waiting', 'finishedCount', 'totalToday');
    }

    private function authorizeBranch(Branch $branch): void
    {
        if ((int) $branch->user_id !== (int) auth()->id()) {
            abort(403);
        }
    }

    private function authorizeQueue(Branch $branch, Queue $queue): void
    {
        if ((int) $queue->branch_id !== (int) $branch->id) {
            abort(404);
        }
    }
}
