<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['nama_cabang', 'kota', 'alamat_lengkap', 'nomor_telepon_cabang', 'rental_id'];

    // Relasi: Cabang milik Rental tertentu
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    // Relasi: Cabang menampung banyak Mobil
    public function mobils()
    {
        return $this->hasMany(Mobil::class);
    }
}