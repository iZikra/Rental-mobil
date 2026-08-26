<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'jumlah_refund',
        'alasan_refund',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik',
        'bukti_transfer',
        'status',
        'alasan_penolakan'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
