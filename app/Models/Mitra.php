<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra_magangs'; // nama tabel sesuai database

    protected $fillable = [
        'user_id',
        'nama_mitra',
        'alamat',
        'kontak',
        'email',
        'pic',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🧩 Tambahkan event untuk hapus user terkait
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($mitra) {
            // Hapus user terkait hanya jika ada
            if ($mitra->user) {
                $mitra->user->delete();
            }
        });
    }

    public function bimbingans()
{
    return $this->hasMany(Bimbingan::class, 'mitra_id');
}

}
