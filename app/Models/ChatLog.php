<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi
    protected $fillable = [
        'user_id',
        'pesan_user',
        'respon_ai',
        'model_used'
    ];

    // Relasi ke User (Opsional, buat skripsi tambah bagus)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}