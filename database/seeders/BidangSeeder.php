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
        $bidangs = [
            'Sekretaris',
            'Bendahara',
            'Humas',
            'Perlengkapan',
            'Keamanan',
            'Kebersihan',
            'Kesehatan',
            'Olahraga dan Seni',
        ];

        foreach ($bidangs as $nama) {
            Bidang::firstOrCreate(['nama' => $nama]);
        }
    }
}
