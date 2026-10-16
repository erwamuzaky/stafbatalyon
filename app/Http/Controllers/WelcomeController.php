<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Event;
use App\Models\Pengaturan;
use App\Models\StafProfile;
use App\Models\User;
use App\Support\BaganStruktur;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Batas jumlah tingkat bagan ringkas di halaman depan.
     */
    private const KEDALAMAN_BAGAN_RINGKAS = 3;

    /**
     * Halaman depan: profil Staf Batalyon SMKN 3 Pariaman.
     *
     * Menampilkan bagan struktur ringkas (staf dari bidang yang ditandai
     * "tampil di halaman depan", dibatasi 3 tingkat), daftar bidang, dan
     * angka agregat (jumlah staf aktif, bidang, kegiatan tahun ini) untuk
     * ringkasan di hero. Struktur lengkap dibuka lewat halaman terpisah.
     * Data pribadi (nomor HP dan email) tidak pernah dikirim ke halaman ini.
     */
    public function __invoke(): View
    {
        $profilsBeranda = BaganStruktur::profilsDisetujui()
            ->filter(fn (StafProfile $profil) => (bool) $profil->bidang?->tampil_di_beranda);

        return view('welcome', [
            'baganRingkas' => BaganStruktur::susun($profilsBeranda, self::KEDALAMAN_BAGAN_RINGKAS),
            'bidang' => Bidang::orderBy('nama')->get(),
            'jumlahStafAktif' => User::where('role', 'staf')->where('status', 'approved')->count(),
            'jumlahBidang' => Bidang::count(),
            'jumlahKegiatanTahunIni' => Event::where('tahun', now()->year)->count(),
            'pengaturan' => Pengaturan::peta(),
            'heroBackground' => Pengaturan::gambar('hero_background'),
            'bagianBackground' => Pengaturan::gambar('bagian_background'),
        ]);
    }
}
