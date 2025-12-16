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
    if (!$user->isPro() && $user->branches()->count() >= 1) {
        return redirect()
            ->route('branches.index')
            ->with('error', 'Paket FREE hanya diperbolehkan memiliki 1 cabang.');
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
            'nomor_antrean_awal' => 'required|integer',
            'jadwal_operasional' => 'required|string',
        ]);

        Branch::create([
            'user_id' => auth()->id(),
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
        // Keamanan: pastikan cabang milik user yang login
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
            'nomor_antrean_awal' => 'required|integer',
            'jadwal_operasional' => 'required|string',
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
