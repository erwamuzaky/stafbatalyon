<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Event;
use App\Models\User;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Halaman depan: profil Staf Batalyon SMKN 3 Pariaman.
     *
     * Menampilkan staf inti yang sudah disetujui dan daftar bidang, ditambah
     * angka agregat (jumlah staf aktif, bidang, kegiatan tahun ini) untuk
     * ringkasan di hero. Data pribadi (nomor HP dan email) tidak pernah
     * dikirim ke halaman ini.
     */
    public function __invoke(): View
    {
        return view('welcome', [
            'stafInti' => User::pesertaAbsensi('inti'),
            'bidang' => Bidang::orderBy('nama')->get(),
            'jumlahStafAktif' => User::where('role', 'staf')->where('status', 'approved')->count(),
            'jumlahBidang' => Bidang::count(),
            'jumlahKegiatanTahunIni' => Event::where('tahun', now()->year)->count(),
        ]);
    }
}
