<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBidangRequest;
use App\Http\Requests\Admin\UpdateBidangRequest;
use App\Models\Bidang;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BidangController extends Controller
{
    /**
     * Daftar bidang beserta jumlah staf di dalamnya.
     */
    public function index(): View
    {
        return view('admin.bidang.index', [
            'bidangs' => Bidang::withCount('stafProfiles')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Form tambah bidang.
     */
    public function create(): View
    {
        return view('admin.bidang.create');
    }

    /**
     * Simpan bidang baru.
     */
    public function store(StoreBidangRequest $request): RedirectResponse
    {
        $bidang = Bidang::create($request->validated());

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang '.$bidang->nama.' sudah ditambahkan.');
    }

    /**
     * Form edit bidang.
     */
    public function edit(Bidang $bidang): View
    {
        return view('admin.bidang.edit', [
            'bidang' => $bidang,
        ]);
    }

    /**
     * Simpan perubahan nama bidang.
     */
    public function update(UpdateBidangRequest $request, Bidang $bidang): RedirectResponse
    {
        $bidang->update($request->validated());

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang '.$bidang->nama.' sudah diperbarui.');
    }

    /**
     * Hapus bidang. Bidang yang masih dipakai staf tidak boleh dihapus.
     */
    public function destroy(Bidang $bidang): RedirectResponse
    {
        $jumlahStaf = $bidang->stafProfiles()->count();

        if ($jumlahStaf > 0) {
            return back()->with('error', 'Bidang '.$bidang->nama.' tidak bisa dihapus karena masih dipakai oleh '.$jumlahStaf.' staf. Pindahkan staf tersebut ke bidang lain lebih dulu.');
        }

        $nama = $bidang->nama;
        $bidang->delete();

        return back()->with('success', 'Bidang '.$nama.' sudah dihapus.');
    }
}
