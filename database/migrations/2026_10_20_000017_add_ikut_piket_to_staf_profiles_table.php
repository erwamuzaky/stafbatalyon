<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->boolean('ikut_piket')->default(true)->after('is_inti');
        });
    }

    public function down(): void
    {
        Schema::table('staf_profiles', function (Blueprint $table) {
            $table->dropColumn('ikut_piket');
        });
    }
};
