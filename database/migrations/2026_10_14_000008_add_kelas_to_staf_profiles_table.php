<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom kelas staf, contoh: XI RPL 1.
     *
     * Dibuat nullable supaya data staf lama tetap aman; kewajiban mengisi
     * ditegakkan lewat validasi pada form.
     */
    public function up(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('bidang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};
