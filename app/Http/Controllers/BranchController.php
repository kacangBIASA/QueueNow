<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Tampilkan daftar cabang (Dashboard Owner)
     */
    public function index()
    {
        $branches = auth()->user()->branches;
        return view('branch.index', compact('branches'));
    }

    /**
     * Tampilkan form tambah cabang
     * (cek batasan FREE / PRO)
     */
    public function create()
    {
        $user = auth()->user();

        // Owner FREE hanya boleh 1 cabang
        if (($user->subscription_type ?? 'free') !== 'pro' && $user->branches()->count() >= 1) {
            return redirect()
                ->route('branches.index')
                ->with('error', 'Paket FREE hanya diperbolehkan memiliki 1 cabang. Upgrade ke PRO untuk cabang tanpa batas.');
        }

        return view('branch.create');
    }

    /**
     * Simpan data cabang ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_antrean_awal' => 'required|integer|min:1',
            'jadwal_operasional' => 'required|string|max:255',
        ]);

        $user = $request->user();

        // Guard ulang di STORE (biar tidak bisa bypass lewat POST langsung)
        if (($user->subscription_type ?? 'free') !== 'pro') {
            $count = Branch::where('user_id', $user->id)->count();
            if ($count >= 1) {
                return back()
                    ->withInput()
                    ->with('error', 'Paket FREE hanya diperbolehkan memiliki 1 cabang. Upgrade ke PRO untuk cabang tanpa batas.');
            }
        }

        Branch::create([
            'user_id' => $user->id,
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'nomor_antrean_awal' => $request->nomor_antrean_awal,
            'jadwal_operasional' => $request->jadwal_operasional,
        ]);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit cabang
     */
    public function edit(Branch $branch)
    {
        if ($branch->user_id !== auth()->id()) {
            abort(403);
        }

        return view('branch.edit', compact('branch'));
    }

    /**
     * Update data cabang
     */
    public function update(Request $request, Branch $branch)
    {
        if ($branch->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_antrean_awal' => 'required|integer|min:1',
            'jadwal_operasional' => 'required|string|max:255',
        ]);

        $branch->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'nomor_antrean_awal' => $request->nomor_antrean_awal,
            'jadwal_operasional' => $request->jadwal_operasional,
        ]);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    /**
     * Hapus cabang
     */
    public function destroy(Branch $branch)
    {
        if ($branch->user_id !== auth()->id()) {
            abort(403);
        }

        $branch->delete();

        return redirect()
            ->route('branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
    