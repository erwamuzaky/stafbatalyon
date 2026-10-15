<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tandai bidang yang stafnya ikut tampil di bagan ringkas halaman depan.
     */
    public function up(): void
    {
        Schema::table('bidangs', function (Blueprint $table) {
            $table->boolean('tampil_di_beranda')->default(false)->after('nama');
        });

        // Supaya perilaku halaman depan tidak berubah: bidang "Pemimpin"
        // yang selama ini dipatok di kode langsung dinyalakan.
        DB::table('bidangs')->where('nama', 'Pemimpin')->update(['tampil_di_beranda' => true]);
    }

    /**
     * Balikkan perubahan.
     */
    public function down(): void
    {
        Schema::table('bidangs', function (Blueprint $table) {
            $table->dropColumn('tampil_di_beranda');
        });
    }
};
