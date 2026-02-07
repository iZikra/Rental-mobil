<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Mobil;
use App\Models\Armada;

class AdminTransaksiController extends Controller
{
    // === HALAMAN UTAMA ADMIN ===
    public function index()
    {
        // PERBAIKAN:
        // 1. with(['user', 'mobil']) -> Mengatasi masalah N+1 Query (Database)
        // 2. paginate(5) -> Mengatasi masalah 7 MB Gambar. 
        //    Kita batasi 5 data per halaman supaya ringan (cuma download 5-10 gambar, bukan puluhan).
        
        $transaksis = \App\Models\Transaksi::with(['user', 'mobil'])
                        ->latest()
                        ->paginate(3); // Ubah angka ini jika ingin 10, tapi saran saya 5 dulu biar ngebut.

        return view('admin.transaksi.index', compact('transaksis'));
    }

    // === TOMBOL TERIMA (APPROVE) ===
// === TOMBOL TERIMA (APPROVE) ===
// === TOMBOL TERIMA (APPROVE) ===
public function approve($id)
{
    $transaksi = Transaksi::findOrFail($id);
    
    // 1. Update status transaksi menjadi Disewa (Sedang Berjalan)
    $transaksi->update(['status' => 'Disewa']);

    // 2. PERBAIKAN LOGIKA: Ambil data mobil dan ubah statusnya di database
    // Ini adalah langkah yang akan membuat mobil HILANG dari dashboard user
    $mobil = Mobil::find($transaksi->mobil_id);
    
    if($mobil) {
        $mobil->status = 'disewa'; // Ubah status menjadi 'disewa'
        $mobil->save();
    }
    
    return redirect()->back()->with('success', 'Mobil otomatis dikunci karena sedang disewa!');
}

    // === ADMIN MENYELESAIKAN PESANAN (MOBIL KEMBALI) ===
    public function complete($id)
    {
        // 1. Cari Transaksi
        $transaksi = Transaksi::findOrFail($id);

        // 2. Ubah Status Transaksi jadi 'Selesai'
        $transaksi->update([
            'status' => 'Selesai'
        ]);

        // 3. [PERBAIKAN UTAMA] Cari Mobil & Ubah Status jadi 'tersedia'
        $mobil = Mobil::findOrFail($transaksi->mobil_id);
        if($mobil) {
            $mobil->update(['status' => 'tersedia']);
        }

        return redirect()->back()->with('success', 'Transaksi selesai. Mobil sekarang tersedia kembali!');
    }

    // === ADMIN MENOLAK PESANAN ===
    public function reject($id)
    {
        // 1. Cari Transaksi
        $transaksi = Transaksi::findOrFail($id);

        // 2. Ubah Status Transaksi jadi 'Ditolak'
        $transaksi->update([
            'status' => 'Ditolak'
        ]);

        // 3. [PENTING JUGA] Jika ditolak, pastikan mobil kembali tersedia
        // Karena saat user booking, status mobil langsung berubah jadi 'disewa'
        $mobil = Mobil::findOrFail($transaksi->mobil_id);
        $mobil->update([
            'status' => 'tersedia'
        ]);

        return redirect()->back()->with('success', 'Pesanan ditolak. Mobil kembali tersedia.');
    }
}