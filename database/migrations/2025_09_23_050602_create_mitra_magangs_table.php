<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_magangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_mitra');
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            // ...existing code...
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_magangs');
        Schema::dropIfExists('mitra_magangs');
    }
};