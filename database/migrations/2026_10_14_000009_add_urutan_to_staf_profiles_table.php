<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Urutan tampil staf. Dipakai buat menentukan komandan teratas di
     * halaman depan (angka terkecil tampil lebih dulu) dan urutan di
     * halaman struktur lengkap.
     *
     * Dibuat nullable supaya data staf lama tetap aman; kalau kosong,
     * urutan jatuh ke nama (A-Z).
     */
    public function up(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->unsignedInteger('urutan')->nullable()->after('is_inti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
