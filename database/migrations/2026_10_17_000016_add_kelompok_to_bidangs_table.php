<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bidangs', function (Blueprint $table) {
            $table->string('kelompok', 20)->default('harian')->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('bidangs', function (Blueprint $table) {
            $table->dropColumn('kelompok');
        });
    }
};
