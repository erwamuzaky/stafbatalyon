<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBaganRequest;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BaganController extends Controller
{
    /**
     * Halaman atur bagan: tiap staf disetujui dikelompokkan per bidang,
     * lengkap dengan pilihan atasan langsungnya.
     */
    public function index(): View
    {
        $perBidang = User::query()
            ->with(['stafProfile.bidang', 'stafProfile.atasan.user'])
            ->where('role', 'staf')
            ->where('status', 'approved')
            ->get()
            ->sortBy([
                fn (User $a, User $b) => strcmp(
                    $a->stafProfile?->bidang?->nama ?? 'zzz',
                    $b->stafProfile?->bidang?->nama ?? 'zzz',
                ),
                fn (User $a, User $b) => ($a->stafProfile?->urutan ?? PHP_INT_MAX) <=> ($b->stafProfile?->urutan ?? PHP_INT_MAX),
                fn (User $a, User $b) => strcmp($a->name, $b->name),
            ])
            ->groupBy(fn (User $user) => $user->stafProfile?->bidang?->nama ?? 'Tanpa Bidang')
            ->sortKeys();

        // "Tanpa Bidang" ditaruh paling akhir.
        if ($perBidang->has('Tanpa Bidang')) {
            $tanpaBidang = $perBidang->pull('Tanpa Bidang');
            $perBidang->put('Tanpa Bidang', $tanpaBidang);
        }

        // Semua profil staf yang disetujui, buat pilihan atasan.
        $profils = StafProfile::query()
            ->with(['user', 'bidang'])
            ->whereHas('user', fn ($query) => $query->where('role', 'staf')->where('status', 'approved'))
            ->get()
            ->sortBy(fn (StafProfile $profil) => $profil->user?->name ?? '')
            ->values();

        return view('admin.bagan.index', [
            'perBidang' => $perBidang,
            'profils' => $profils,
        ]);
    }

    /**
     * Simpan perubahan atasan langsung tiap staf.
     */
    public function update(UpdateBaganRequest $request): RedirectResponse
    {
        $data = $request->validated('atasan') ?? [];

        DB::transaction(function () use ($data) {
            foreach ($data as $id => $atasanId) {
                StafProfile::query()
                    ->whereKey($id)
                    ->update([
                        'atasan_id' => ($atasanId === null || $atasanId === '') ? null : (int) $atasanId,
                    ]);
            }
        });

        return redirect()->route('admin.bagan.index')
            ->with('success', 'Bagan atasan sudah disimpan.');
    }
}
