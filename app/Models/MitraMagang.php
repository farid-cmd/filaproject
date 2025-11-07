<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraMagang extends Model
{
    use HasFactory;

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
}
