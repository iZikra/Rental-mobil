<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $table = 'mobils';
    protected $fillable = [
        'merk',
        'model',
        'no_plat',
        'harga_sewa',
        'gambar',
        'status',
        'deskripsi',
    ];
}