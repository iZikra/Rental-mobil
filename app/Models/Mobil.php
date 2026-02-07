<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    // UPDATE 1: Tambahkan kolom baru ke fillable
    protected $fillable = [
        'rental_id', // <--- BARU
        'branch_id', // <--- BARU
        'merk',
        'model',
        'nopol',
        'harga_sewa',
        // ... kolom lama lainnya ...
        'status'
    ];

    // UPDATE 2: Tambahkan Relasi ke Rental & Branch
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    // ... relasi existing (transaksi) ...
}