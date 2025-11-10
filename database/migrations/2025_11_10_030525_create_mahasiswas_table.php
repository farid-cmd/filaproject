<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();

            // Kolom foreign key
            $table->foreignId('user_id')
                  ->constrained('users')           // references('id')->on('users')
                  ->cascadeOnDelete();             // lebih ringkas dari ->onDelete('cascade')

            // Kolom data mahasiswa
            $table->string('nim', 20)->unique();   // batasi panjang NIM
            $table->string('jurusan', 100)->nullable();
            $table->string('angkatan', 4)->nullable(); // tahun masuk (contoh: 2021)

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
