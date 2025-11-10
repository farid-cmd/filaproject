<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembimbing_lapangans', function (Blueprint $table) {
    // $table->id();
    // $table->unsignedBigInteger('user_id')->nullable(); // ✨ ini penting
    $table->unsignedBigInteger('mitra_id');
    $table->string('nip');
    $table->string('nama');
    $table->string('jabatan');
    $table->string('kontak');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('pembimbing_lapangans');
    }
};
