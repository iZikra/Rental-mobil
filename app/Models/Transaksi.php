<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobil_id',
        'nama',
        'no_hp',
        'alamat',
        'foto_identitas', // Pastikan kolom ini ada di database
        'tgl_ambil',
        'jam_ambil',
        'tgl_kembali',
        'jam_kembali',
        'lokasi_ambil',
        'lokasi_kembali',
        'alamat_lengkap',
        'alamat_jemput', // Alamat jemput/antar
        'alamat_antar',  // Alamat pengantaran
        'tujuan',
        'sopir',          // dengan_sopir / tanpa_sopir
        'lama_sewa',
        'total_harga',
        'status',         // Pending, Approved, etc
        'bukti_bayar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'mobil_id');
    }
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}