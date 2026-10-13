<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu staf hanya boleh sekali pada tanggal dan sesi yang sama.
     */
    public function up(): void
    {
        Schema::table('piket_gerbang', function (Blueprint $table) {
            $table->unique(['tanggal', 'sesi', 'user_id'], 'piket_gerbang_slot_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piket_gerbang', function (Blueprint $table) {
            $table->dropUnique('piket_gerbang_slot_unik');
        });
    }
};
