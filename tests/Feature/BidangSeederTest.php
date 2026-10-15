<?php

namespace Tests\Feature;

use Database\Seeders\BidangSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidangSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seeder menandai bidang "Pemimpin" agar stafnya tampil di bagan halaman
     * depan, sementara bidang lain tetap tidak ditandai.
     */
    public function test_seeder_menandai_bidang_pemimpin_untuk_halaman_depan(): void
    {
        $this->seed(BidangSeeder::class);

        $this->assertDatabaseHas('bidangs', [
            'nama' => 'Pemimpin',
            'tampil_di_beranda' => 1,
        ]);

        $this->assertDatabaseHas('bidangs', [
            'nama' => 'Humas',
            'tampil_di_beranda' => 0,
        ]);
    }
}
