<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Support\BaganStruktur;
use Illuminate\View\View;

class StrukturController extends Controller
{
    /**
     * Halaman struktur lengkap: bagan bercabang dari data atasan langsung
     * tiap staf yang sudah disetujui. Semua staf tampil (tanpa batas tingkat).
     */
    public function __invoke(): View
    {
        return view('struktur', [
            'pengaturan' => Pengaturan::peta(),
            'bagan' => BaganStruktur::susun(BaganStruktur::profilsDisetujui()),
        ]);
    }
}
