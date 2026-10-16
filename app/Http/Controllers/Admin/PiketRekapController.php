<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RekapPiketRequest;
use App\Models\Bidang;
use App\Models\PiketGerbang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PiketRekapController extends Controller
{
    /**
     * Ukuran kertas dan arah cetak rekap.
     */
    private const KERTAS = 'a4';

    /**
     * Arah kertas rekap.
     */
    private const ORIENTASI = 'portrait';

    /**
     * Kota yang dipakai pada blok tanda tangan.
     */
    private const TEMPAT = 'Pariaman';

    /**
     * Jabatan penandatangan rekap.
     */
    private const JABATAN = 'Sekretaris Batalyon';

    /**
     * Jumlah tanggal piket yang ditampilkan ringkas sebelum dipotong.
     */
    private const BATAS_TANGGAL = 6;

    /**
     * Rekap & pemerataan piket gerbang untuk admin.
     */
    public function index(RekapPiketRequest $request): View
    {
        return view('admin.piket.rekap', $this->data($request));
    }

    /**
     * Cetak rekap piket gerbang dalam bentuk PDF ukuran A4.
     */
    public function cetak(RekapPiketRequest $request): Response
    {
        $data = $this->data($request);

        $pdf = Pdf::loadView('pdf.rekap-piket', $data)
            ->setPaper(self::KERTAS, self::ORIENTASI);

        $namaBerkas = 'rekap-piket-'.$data['dari']->toDateString().'-'.$data['sampai']->toDateString().'.pdf';

        return $pdf->stream($namaBerkas);
    }

    /**
     * Susun seluruh data rekap dari satu kali pengambilan piket.
     *
     * @return array<string, mixed>
     */
    private function data(RekapPiketRequest $request): array
    {
        [$dari, $sampai] = $this->rentang($request);
        $bidangId = $this->bidangId($request);

        $bidangPilihan = Bidang::orderBy('nama')->get();

        // Ambil semua piket dalam rentang beserta relasinya sekali jalan,
        // lalu dihitung di memori supaya tidak ada query di dalam loop.
        $piket = PiketGerbang::query()
            ->with(['user.stafProfile.bidang'])
            ->whereBetween('tanggal', [
                $dari->copy()->startOfDay(),
                $sampai->copy()->endOfDay(),
            ])
            ->orderBy('tanggal')
            ->get();

        $perStaf = $this->kelompokkanPerStaf($piket, $bidangId);
        $tabel = $this->susunTabel($perStaf);
        $stafEligible = $this->stafEligible($bidangId);

        return [
            'dari' => $dari,
            'sampai' => $sampai,
            'bidangId' => $bidangId,
            'namaBidang' => $bidangPilihan->firstWhere('id', $bidangId)?->nama,
            'bidangPilihan' => $bidangPilihan,
            'sesi' => PiketGerbang::SESI,
            'tabel' => $tabel,
            'ringkasan' => $this->ringkasan($tabel),
            'berurutan' => $this->piketBerurutan($perStaf),
            'belum' => $this->belumDapatPiket($stafEligible, $perStaf),
            'tempat' => self::TEMPAT,
            'jabatan' => self::JABATAN,
            'batasTanggal' => self::BATAS_TANGGAL,
        ];
    }

    /**
     * Tentukan rentang tanggal yang dipakai (bawaan: awal bulan ini sampai hari ini).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rentang(RekapPiketRequest $request): array
    {
        $dari = $request->filled('dari')
            ? Carbon::parse((string) $request->input('dari'))
            : now()->startOfMonth();

        $sampai = $request->filled('sampai')
            ? Carbon::parse((string) $request->input('sampai'))
            : now();

        return [
            $dari->startOfDay()->locale('id'),
            $sampai->startOfDay()->locale('id'),
        ];
    }

    /**
     * Id bidang yang dipilih, atau null kalau tanpa penyaring bidang.
     */
    private function bidangId(RekapPiketRequest $request): ?int
    {
        $id = $request->integer('bidang_id');

        return $id > 0 ? $id : null;
    }

    /**
     * Kelompokkan piket per staf, sekaligus hitung total dan jumlah per sesi.
     *
     * @param  Collection<int, PiketGerbang>  $piket
     * @return array<int, array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>}>
     */
    private function kelompokkanPerStaf(Collection $piket, ?int $bidangId): array
    {
        $sesi = array_keys(PiketGerbang::SESI);
        $hasil = [];

        foreach ($piket as $baris) {
            $user = $baris->user;

            if (! $user instanceof User) {
                continue;
            }

            if ($bidangId !== null && (int) $user->stafProfile?->bidang_id !== $bidangId) {
                continue;
            }

            if (! isset($hasil[$user->id])) {
                $hasil[$user->id] = [
                    'user' => $user,
                    'total' => 0,
                    'perSesi' => array_fill_keys($sesi, 0),
                    'tanggal' => [],
                ];
            }

            $hasil[$user->id]['total']++;
            $hasil[$user->id]['perSesi'][$baris->sesi] = ($hasil[$user->id]['perSesi'][$baris->sesi] ?? 0) + 1;
            $hasil[$user->id]['tanggal'][] = $baris->tanggal->copy();
        }

        return $hasil;
    }

    /**
     * Susun baris tabel per staf, urut dari piket terbanyak.
     *
     * @param  array<int, array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>}>  $perStaf
     * @return list<array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>, terbanyak: bool, tersedikit: bool}>
     */
    private function susunTabel(array $perStaf): array
    {
        if ($perStaf === []) {
            return [];
        }

        $daftar = array_values($perStaf);

        usort($daftar, function (array $a, array $b): int {
            return $b['total'] <=> $a['total'] ?: strcmp($a['user']->name, $b['user']->name);
        });

        $totalPiket = array_column($daftar, 'total');
        $maks = max($totalPiket);
        $min = min($totalPiket);
        $adaSelisih = $maks !== $min;

        $tabel = [];

        foreach ($daftar as $item) {
            $item['tanggal'] = $this->tanggalUnik($item['tanggal']);
            $item['terbanyak'] = $adaSelisih && $item['total'] === $maks;
            $item['tersedikit'] = $adaSelisih && $item['total'] === $min;

            $tabel[] = $item;
        }

        return $tabel;
    }

    /**
     * Angka ringkasan di bagian atas halaman.
     *
     * @param  list<array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>, terbanyak: bool, tersedikit: bool}>  $tabel
     * @return array{total: int, jumlahStaf: int, rataRata: float, terbanyak: int, tersedikit: int, namaTerbanyak: list<string>, namaTersedikit: list<string>}
     */
    private function ringkasan(array $tabel): array
    {
        if ($tabel === []) {
            return [
                'total' => 0,
                'jumlahStaf' => 0,
                'rataRata' => 0.0,
                'terbanyak' => 0,
                'tersedikit' => 0,
                'namaTerbanyak' => [],
                'namaTersedikit' => [],
            ];
        }

        $totalPerStaf = array_column($tabel, 'total');
        $total = array_sum($totalPerStaf);
        $jumlahStaf = count($tabel);
        $maks = max($totalPerStaf);
        $min = min($totalPerStaf);

        $namaTerbanyak = [];
        $namaTersedikit = [];

        foreach ($tabel as $baris) {
            if ($baris['total'] === $maks) {
                $namaTerbanyak[] = $baris['user']->name;
            }

            if ($baris['total'] === $min) {
                $namaTersedikit[] = $baris['user']->name;
            }
        }

        return [
            'total' => $total,
            'jumlahStaf' => $jumlahStaf,
            'rataRata' => round($total / $jumlahStaf, 1),
            'terbanyak' => $maks,
            'tersedikit' => $min,
            'namaTerbanyak' => $namaTerbanyak,
            'namaTersedikit' => $namaTersedikit,
        ];
    }

    /**
     * Cari staf yang dapat piket pada hari-hari berurutan (2 hari atau lebih).
     *
     * @param  array<int, array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>}>  $perStaf
     * @return list<array{user: User, rentang: list<array{dari: Carbon, sampai: Carbon, jumlah: int}>}>
     */
    private function piketBerurutan(array $perStaf): array
    {
        $hasil = [];

        foreach ($perStaf as $data) {
            $rentang = $this->rentangBerurutan($this->tanggalUnik($data['tanggal']));

            if ($rentang === []) {
                continue;
            }

            $hasil[] = [
                'user' => $data['user'],
                'rentang' => $rentang,
            ];
        }

        return $hasil;
    }

    /**
     * Rangkaikan tanggal yang berselisih satu hari menjadi rentang berurutan.
     *
     * @param  list<Carbon>  $tanggal  Tanggal unik yang sudah urut naik.
     * @return list<array{dari: Carbon, sampai: Carbon, jumlah: int}>
     */
    private function rentangBerurutan(array $tanggal): array
    {
        $hasil = [];
        $awal = null;
        $sebelumnya = null;
        $jumlah = 0;

        foreach ($tanggal as $tgl) {
            $nyambung = $sebelumnya !== null && $sebelumnya->copy()->addDay()->isSameDay($tgl);

            if ($nyambung) {
                $jumlah++;
            } else {
                $this->catatRentang($hasil, $awal, $sebelumnya, $jumlah);
                $awal = $tgl;
                $jumlah = 1;
            }

            $sebelumnya = $tgl;
        }

        $this->catatRentang($hasil, $awal, $sebelumnya, $jumlah);

        return $hasil;
    }

    /**
     * Simpan satu rentang kalau panjangnya 2 hari atau lebih.
     *
     * @param  list<array{dari: Carbon, sampai: Carbon, jumlah: int}>  $hasil
     */
    private function catatRentang(array &$hasil, ?Carbon $awal, ?Carbon $akhir, int $jumlah): void
    {
        if ($awal === null || $akhir === null || $jumlah < 2) {
            return;
        }

        $hasil[] = [
            'dari' => $awal,
            'sampai' => $akhir,
            'jumlah' => $jumlah,
        ];
    }

    /**
     * Buang tanggal kembar dan urutkan naik.
     *
     * @param  list<Carbon>  $tanggal
     * @return list<Carbon>
     */
    private function tanggalUnik(array $tanggal): array
    {
        $unik = [];

        foreach ($tanggal as $tgl) {
            $unik[$tgl->toDateString()] = $tgl;
        }

        ksort($unik);

        return array_values($unik);
    }

    /**
     * Staf yang bisa dapat piket: role staf dan sudah disetujui.
     *
     * @return Collection<int, User>
     */
    private function stafEligible(?int $bidangId): Collection
    {
        return User::query()
            ->with('stafProfile.bidang')
            ->where('role', 'staf')
            ->where('status', 'approved')
            ->when(
                $bidangId !== null,
                fn ($query) => $query->whereHas(
                    'stafProfile',
                    fn ($profil) => $profil->where('bidang_id', $bidangId),
                ),
            )
            ->orderBy('name')
            ->get();
    }

    /**
     * Staf yang diizinkan piket tapi belum mendapat piket pada rentang ini.
     *
     * @param  Collection<int, User>  $stafEligible
     * @param  array<int, array{user: User, total: int, perSesi: array<string, int>, tanggal: list<Carbon>}>  $perStaf
     * @return list<User>
     */
    private function belumDapatPiket(Collection $stafEligible, array $perStaf): array
    {
        $sudahDapat = array_keys($perStaf);
        $belum = [];

        foreach ($stafEligible as $staf) {
            if (! in_array($staf->id, $sudahDapat, true)) {
                $belum[] = $staf;
            }
        }

        return $belum;
    }
}
