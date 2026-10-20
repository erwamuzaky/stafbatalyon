<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CetakAbsensiRequest;
use App\Models\Bidang;
use App\Models\Event;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    /**
     * Pilihan penyaring peserta beserta labelnya.
     *
     * @var array<string, string>
     */
    public const FILTER = User::PESERTA_FILTER;

    /**
     * Ukuran kertas dan arah cetak.
     */
    private const KERTAS = 'a4';

    /**
     * Arah kertas absensi.
     */
    private const ORIENTASI = 'portrait';

    /**
     * Batas jumlah baris kosong tambahan untuk tamu.
     */
    private const BARIS_KOSONG_MAKSIMAL = 20;

    /**
     * Jabatan penandatangan yang dipakai kalau admin tidak mengisinya.
     */
    private const JABATAN_DEFAULT = 'Sekretaris Batalyon';

    /**
     * Kota yang dipakai pada blok tanda tangan.
     */
    private const TEMPAT = 'Pariaman';

    /**
     * Form cetak absensi.
     */
    public function create(): View
    {
        return view('admin.absensi.create', [
            'eventPilihan' => Event::orderByDesc('tahun')->orderBy('nama')->get(),
            'bidangPilihan' => Bidang::orderBy('nama')->get(),
            'filterPilihan' => self::FILTER,
            'barisKosongMaksimal' => self::BARIS_KOSONG_MAKSIMAL,
            'jabatanDefault' => self::JABATAN_DEFAULT,
        ]);
    }

    /**
     * Buat berkas PDF daftar hadir, lalu tampilkan atau unduh.
     */
    public function cetak(CetakAbsensiRequest $request): Response
    {
        $data = $this->dataAbsensi($request);

        $pdf = Pdf::loadView('pdf.absensi', $data)
            ->setPaper(self::KERTAS, self::ORIENTASI);

        $namaBerkas = $this->namaBerkas($data['namaKegiatan'], $data['tanggal']);

        if ($request->input('aksi') === 'unduh') {
            return $pdf->download($namaBerkas);
        }

        return $pdf->stream($namaBerkas);
    }

    /**
     * Susun data yang dipakai template PDF.
     *
     * @return array<string, mixed>
     */
    private function dataAbsensi(CetakAbsensiRequest $request): array
    {
        $filter = (string) $request->input('filter', 'semua');
        $bidangId = $request->integer('bidang_id');
        $tanggal = Carbon::parse((string) $request->input('tanggal'))->locale('id');

        $peserta = User::pesertaAbsensi($filter, $bidangId > 0 ? $bidangId : null);

        return [
            'namaKegiatan' => (string) $request->input('nama_kegiatan'),
            'tanggal' => $tanggal,
            'keteranganPeserta' => $this->keteranganPeserta($filter, $request->namaBidang()),
            'peserta' => $peserta,
            'kelompokBidang' => $peserta->groupBy(
                fn (User $orang): string => $orang->stafProfile?->bidang?->nama ?? 'Tanpa Bidang',
            ),
            'barisKosong' => (int) $request->input('baris_kosong', 0),
            'penandatangan' => $request->input('penandatangan'),
            'jabatan' => $request->input('jabatan') ?: self::JABATAN_DEFAULT,
            'tempat' => self::TEMPAT,
        ];
    }

    /**
     * Keterangan penyaring peserta yang dicetak di kepala tabel.
     */
    private function keteranganPeserta(string $filter, ?string $namaBidang): string
    {
        if ($filter === 'bidang' && $namaBidang !== null) {
            return 'Bidang '.$namaBidang;
        }

        return self::FILTER[$filter] ?? self::FILTER['semua'];
    }

    /**
     * Nama berkas PDF, misalnya absensi-latihan-dasar-2026-10-13.pdf.
     */
    private function namaBerkas(string $namaKegiatan, Carbon $tanggal): string
    {
        $slug = Str::slug($namaKegiatan);

        if ($slug === '') {
            $slug = 'kegiatan';
        }

        return 'absensi-'.$slug.'-'.$tanggal->toDateString().'.pdf';
    }
}
