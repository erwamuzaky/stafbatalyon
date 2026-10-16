<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuratRequest;
use App\Http\Requests\Admin\UpdateSuratRequest;
use App\Models\Event;
use App\Models\Pengaturan;
use App\Models\Periode;
use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SuratController extends Controller
{
    /**
     * Daftar surat dengan filter jenis dan pencarian.
     */
    public function index(Request $request): View
    {
        $jenis = $request->query('jenis');
        $q = $request->query('q');

        $surats = Surat::with(['periode', 'event'])
            ->when(is_string($jenis) && $jenis !== '', fn ($query) => $query->where('jenis', $jenis))
            ->when(
                is_string($q) && $q !== '',
                fn ($query) => $query->where('perihal', 'like', '%'.$q.'%')
                    ->orWhere('nomor', 'like', '%'.$q.'%')
                    ->orWhere('penandatangan', 'like', '%'.$q.'%')
            )
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('admin.surat.index', [
            'surats' => $surats,
            'jenisPilihan' => Surat::JENIS,
            'jenis' => $jenis,
            'q' => $q,
        ]);
    }

    /**
     * Form tambah surat.
     */
    public function create(): View
    {
        return view('admin.surat.create', [
            'jenisPilihan' => Surat::JENIS,
            'periodes' => Periode::orderByDesc('tahun_mulai')->orderByDesc('tanggal_mulai')->get(),
            'events' => Event::orderByDesc('tahun')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan surat baru. Nomor otomatis kalau tidak diisi.
     */
    public function store(StoreSuratRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['nomor'])) {
            $generated = Surat::buatNomor($data['jenis'], Carbon::parse($data['tanggal']));
            $data['nomor_urut'] = $generated['nomor_urut'];
            $data['nomor'] = $generated['nomor'];
        }

        unset($data['berkas']);

        $surat = Surat::create($data);

        return redirect()->route('admin.surat.index')
            ->with('success', $surat->labelJenis().' nomor '.$surat->nomor.' sudah ditambahkan.');
    }

    /**
     * Form ubah surat.
     */
    public function edit(Surat $surat): View
    {
        return view('admin.surat.edit', [
            'surat' => $surat,
            'jenisPilihan' => Surat::JENIS,
            'periodes' => Periode::orderByDesc('tahun_mulai')->orderByDesc('tanggal_mulai')->get(),
            'events' => Event::orderByDesc('tahun')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan perubahan surat.
     */
    public function update(UpdateSuratRequest $request, Surat $surat): RedirectResponse
    {
        $data = $request->validated();

        unset($data['berkas']);

        $surat->update($data);

        return redirect()->route('admin.surat.index')
            ->with('success', 'Surat '.$surat->nomor.' sudah diperbarui.');
    }

    /**
     * Hapus surat beserta berkas arsipnya.
     */
    public function destroy(Surat $surat): RedirectResponse
    {
        $nomor = $surat->nomor;
        $surat->delete();

        return back()->with('success', 'Surat '.$nomor.' sudah dihapus.');
    }

    /**
     * Cetak PDF surat. Kalau berkas belum tersimpan, unduh langsung.
     */
    public function cetak(Surat $surat): Response
    {
        $data = [
            'surat' => $surat,
            'kopNama' => Pengaturan::ambil('situs_nama', 'Staf Batalyon'),
            'kopSekolah' => Pengaturan::ambil('situs_sekolah', 'SMKN 3 Pariaman'),
        ];

        $pdf = Pdf::loadView('pdf.surat', $data)
            ->setPaper('a4', 'portrait');

        $namaBerkas = 'surat-'.$surat->id.'.pdf';

        return $pdf->stream($namaBerkas);
    }
}
