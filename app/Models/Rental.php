<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    
    protected $guarded = ['id']; // Izinkan semua kolom diisi kecuali ID

    // Relasi: Rental milik satu User (Owner)
    public function user()
    {
        return $this->belongsTo(User::class);
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