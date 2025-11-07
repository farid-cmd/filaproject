<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'mahasiswa_id',
        'mitra_id',
        'status',
    ];

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function PembimbingLapangan()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    // Relasi ke Mitra Magang
    public function mitra()
    {
        return $this->belongsTo(MitraMagang::class, 'mitra_id');
    }
}
