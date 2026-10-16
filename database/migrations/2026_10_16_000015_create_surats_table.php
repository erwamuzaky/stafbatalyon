<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 20);
            $table->unsignedInteger('nomor_urut')->nullable();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->string('perihal');
            $table->string('tujuan')->nullable();
            $table->text('isi')->nullable();
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('penandatangan');
            $table->string('jabatan');
            $table->string('tempat')->default('Pariaman');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
