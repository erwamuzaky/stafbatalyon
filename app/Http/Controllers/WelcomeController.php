<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Halaman depan: profil Staf Batalyon SMKN 3 Pariaman.
     *
     * Menampilkan staf inti yang sudah disetujui dan daftar bidang.
     * Data pribadi (nomor HP dan email) tidak pernah dikirim ke halaman ini.
     */
    public function __invoke(): View
    {
        return view('welcome', [
            'stafInti' => User::pesertaAbsensi('inti'),
            'bidang' => Bidang::orderBy('nama')->get(),
        ]);
    }
}
