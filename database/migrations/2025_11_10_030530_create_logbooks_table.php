<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel mahasiswa pakai NIM
            $table->string('mahasiswa_nim')->index();

            $table->foreign('mahasiswa_nim')
                ->references('nim')
                ->on('mahasiswas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->date('tanggal')->nullable();
            $table->text('kegiatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
