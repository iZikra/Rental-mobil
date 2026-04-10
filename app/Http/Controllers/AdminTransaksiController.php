<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class AdminTransaksiController extends Controller
{
    /**
     * Halaman Audit Transaksi — Admin hanya bisa MELIHAT (read-only).
     * Approval, konfirmasi, dan penolakan pesanan adalah wewenang Mitra.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['user', 'mobil.rental'])
                        ->latest()
                        ->paginate(10);

        return view('admin.transaksi.index', compact('transaksis'));
    }
}