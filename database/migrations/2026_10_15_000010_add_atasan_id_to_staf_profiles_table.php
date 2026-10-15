<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Atasan langsung tiap staf, menunjuk ke profil staf lain.
     *
     * Dipakai buat mengatur bagan struktur yang bercabang: satu staf bisa
     * punya satu atasan langsung, dan dari sini nanti digambar susunannya.
     * Dibuat nullable supaya staf tanpa atasan (mis. komandan teratas) tetap
     * aman; kalau atasan dihapus, kolom ini otomatis jadi kosong.
     */
    public function up(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->foreignId('atasan_id')
                ->nullable()
                ->after('bidang_id')
                ->constrained('staf_profiles')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->dropForeign(['atasan_id']);
            $table->dropColumn('atasan_id');
        });
    }
};
