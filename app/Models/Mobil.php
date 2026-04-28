<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mobil extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'branch_id',
        'merk',
        'model',
        'no_plat', // Sesuaikan dengan database (no_plat atau nopol?)
        'harga_sewa',
        'tipe_mobil',
        'tahun_buat',
        'transmisi',
        'bahan_bakar',
        'jumlah_kursi',
        'gambar',
        'deskripsi',
        'status' // Kunci utama untuk filter katalog
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = (string) ($this->gambar ?? '');
        if ($path === '') {
            return asset('img/default-car.png'); // Fallback image
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // KITA TEMBAK LANGSUNG KE JALUR YANG SUDAH TERBUKTI BERHASIL DI HOSTING
        return url('public/storage/' . $path);
    }
}
