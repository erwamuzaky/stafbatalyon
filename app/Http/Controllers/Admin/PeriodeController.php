<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeRequest;
use App\Http\Requests\Admin\UpdatePeriodeRequest;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    /**
     * Daftar periode kepengurusan beserta jumlah kegiatan dan serah terimanya.
     */
    public function index(): View
    {
        return view('admin.periode.index', [
            'periodes' => Periode::withCount(['events', 'serahTerima'])
                ->orderByDesc('tahun_mulai')
                ->orderByDesc('tanggal_mulai')
                ->get(),
        ]);
    }

    /**
     * Form tambah periode.
     */
    public function create(): View
    {
        return view('admin.periode.create', [
            'statuses' => Periode::STATUS,
        ]);
    }

    /**
     * Simpan periode baru. Kalau berstatus aktif, periode aktif lain dimatikan.
     */
    public function store(StorePeriodeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (($data['status'] ?? null) === 'aktif') {
            $this->nonaktifkanLain();
        }

        $periode = Periode::create($data);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode '.$periode->nama.' sudah ditambahkan.');
    }

    /**
     * Form edit periode.
     */
    public function edit(Periode $periode): View
    {
        return view('admin.periode.edit', [
            'periode' => $periode,
            'statuses' => Periode::STATUS,
        ]);
    }

    /**
     * Simpan perubahan periode.
     */
    public function update(UpdatePeriodeRequest $request, Periode $periode): RedirectResponse
    {
        $data = $request->validated();

        if (($data['status'] ?? null) === 'aktif') {
            $this->nonaktifkanLain($periode->id);
        }

        $periode->update($data);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode '.$periode->nama.' sudah diperbarui.');
    }

    /**
     * Hapus periode. Periode yang masih punya kegiatan atau serah terima tidak boleh dihapus.
     */
    public function destroy(Periode $periode): RedirectResponse
    {
        $kegiatan = $periode->events()->count();
        $serah = $periode->serahTerima()->count() + $periode->serahTerimaSebagaiLama()->count();

        if ($kegiatan > 0 || $serah > 0) {
            return back()->with('error', 'Periode '.$periode->nama.' tidak bisa dihapus karena masih terhubung dengan '.$kegiatan.' kegiatan dan '.$serah.' data serah terima.');
        }

        $nama = $periode->nama;
        $periode->delete();

        return back()->with('success', 'Periode '.$nama.' sudah dihapus.');
    }

    /**
     * Jadikan periode aktif lain menjadi selesai.
     */
    private function nonaktifkanLain(?int $kecualiId = null): void
    {
        Periode::query()
            ->where('status', 'aktif')
            ->when($kecualiId !== null, fn ($query) => $query->whereKeyNot($kecualiId))
            ->update(['status' => 'selesai']);
    }
}
