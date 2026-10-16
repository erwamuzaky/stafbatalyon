<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSerahTerimaRequest;
use App\Http\Requests\Admin\UpdateSerahTerimaRequest;
use App\Models\Periode;
use App\Models\SerahTerima;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SerahTerimaController extends Controller
{
    /**
     * Daftar catatan serah terima antar periode.
     */
    public function index(): View
    {
        return view('admin.serah-terima.index', [
            'serahTerima' => SerahTerima::with(['periode', 'periodeLama'])
                ->orderByDesc('tanggal')
                ->get(),
        ]);
    }

    /**
     * Form catat serah terima.
     */
    public function create(): View
    {
        return view('admin.serah-terima.create', [
            'periodes' => $this->daftarPeriode(),
        ]);
    }

    /**
     * Simpan catatan serah terima baru.
     */
    public function store(StoreSerahTerimaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['file_path'] = $request->hasFile('berkas')
            ? $request->file('berkas')->store('serah-terima', 'public')
            : null;

        unset($data['berkas']);

        $serahTerima = SerahTerima::create($data);

        return redirect()->route('admin.serah-terima.index')
            ->with('success', 'Serah terima untuk periode '.$serahTerima->periode->nama.' sudah dicatat.');
    }

    /**
     * Form edit serah terima.
     */
    public function edit(SerahTerima $serahTerima): View
    {
        return view('admin.serah-terima.edit', [
            'serahTerima' => $serahTerima,
            'periodes' => $this->daftarPeriode(),
        ]);
    }

    /**
     * Simpan perubahan serah terima. Berkas lama diganti kalau ada unggahan baru.
     */
    public function update(UpdateSerahTerimaRequest $request, SerahTerima $serahTerima): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('berkas')) {
            if ($serahTerima->file_path) {
                Storage::disk('public')->delete($serahTerima->file_path);
            }

            $data['file_path'] = $request->file('berkas')->store('serah-terima', 'public');
        }

        unset($data['berkas']);

        $serahTerima->update($data);

        return redirect()->route('admin.serah-terima.index')
            ->with('success', 'Serah terima sudah diperbarui.');
    }

    /**
     * Hapus catatan serah terima.
     */
    public function destroy(SerahTerima $serahTerima): RedirectResponse
    {
        $serahTerima->delete();

        return back()->with('success', 'Data serah terima sudah dihapus.');
    }

    /**
     * Daftar periode untuk pilihan pada form.
     *
     * @return Collection<int, Periode>
     */
    private function daftarPeriode(): Collection
    {
        return Periode::orderByDesc('tahun_mulai')->orderByDesc('tanggal_mulai')->get();
    }
}
