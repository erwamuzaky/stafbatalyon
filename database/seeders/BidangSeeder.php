<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Seed the bidangs table.
     */
    public function run(): void
    {
        // Nama bidang => penanda "tampil di bagan halaman depan".
        $bidangs = [
            'Pemimpin' => true,
            'Sekretaris' => false,
            'Bendahara' => false,
            'Humas' => false,
            'Perlengkapan' => false,
            'Keamanan' => false,
            'Kebersihan' => false,
            'Kesehatan' => false,
            'Olahraga dan Seni' => false,
            'Poltar' => false,
            'Poltir' => false,
        ];

        foreach ($bidangs as $nama => $tampilDiBeranda) {
            Bidang::firstOrCreate(
                ['nama' => $nama],
                ['tampil_di_beranda' => $tampilDiBeranda],
            );
        }
    }
}
