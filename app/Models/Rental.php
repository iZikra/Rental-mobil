<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'nama_rental',
        'slug',
        'no_telp_bisnis',
        'alamat',
        'deskripsi',
        'logo',
        'no_izin_usaha',
        'nomor_rekening',
        'bank',
        'nama_bank',
        'no_rekening',
        'atas_nama_rekening',
        'syarat_ketentuan',
        'biaya_sopir_per_hari',
        'biaya_bandara_per_trip',
        'status',
    ];
    // Relasi: Rental milik satu User (Owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu Rental bisa dimiliki oleh banyak User (Bos dan Karyawan)
    public function users()
    {
        return $this->hasMany(User::class, 'rental_id');
    }
    // Relasi: Rental punya banyak Cabang
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    // Relasi: Rental punya banyak Mobil (melalui cabang atau langsung)
    public function mobils()
    {
        return $this->hasMany(Mobil::class);
    }
    
    // Relasi: Rental punya banyak Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
