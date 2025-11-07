<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = [
        'mahasiswa_nim',
        'tanggal',
        'kegiatan',
    ];

    // relasi ke Mahasiswa lewat nim
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
}
