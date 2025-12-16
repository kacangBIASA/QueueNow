<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerQueueController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Ambil semua cabang milik owner (kalau temanmu sudah buat multi cabang, ini sudah siap)
        $branches = Branch::where('user_id', $user->id)->orderBy('id')->get();

        // Untuk UI, pilih cabang pertama sebagai default tampil
        $activeBranch = $branches->first();

        $data = null;
        if ($activeBranch) {
            $data = $this->getQueueData($activeBranch->id, $today);
        }

        return view('owner.queues.index', compact('branches', 'activeBranch', 'today', 'data'));
    }

    public function callNext(Branch $branch)
    {
        $this->authorizeBranch($branch);

        $today = now()->toDateString();

        DB::transaction(function () use ($branch, $today) {
            // jika ada called yang masih aktif, biarkan (opsional)
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
            }
        });

        return back()->with('success', 'Memanggil antrean berikutnya.');
    }

    public function skip(Branch $branch, Queue $queue)
    {
        $this->authorizeBranch($branch);
        $this->authorizeQueue($branch, $queue);

        $queue->update(['status' => 'skipped']);

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
            ->orderBy('called_at', 'desc')
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
        if ($branch->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeQueue(Branch $branch, Queue $queue): void
    {
        if ($queue->branch_id !== $branch->id) {
            abort(404);
        }
    }
}
