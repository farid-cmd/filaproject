<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingLapangan extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_lapangans'; // pastikan sesuai dengan nama tabel

    protected $fillable = [
        'user_id',
        'mitra_id',
        'nip',
        'nama',
        'jabatan',
        'kontak',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke MitraMagang
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
        
    }

     public function bimbingans()
    {
    return $this->hasMany(Bimbingan::class);
    }
}
