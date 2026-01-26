<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    // 1. HALAMAN RIWAYAT
    public function index()
    {
        // Menggunakan nama variabel $transaksis (jamak) sesuai perbaikan view sebelumnya
        $transaksis = Transaksi::where('user_id', Auth::id())
                        ->with('mobil')
                        ->latest()
                        ->get();

        return view('pages.riwayat', compact('transaksis'));
    }

    // 2. PROSES ORDER (SIMPAN)
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'mobil_id' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'tgl_ambil' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_ambil',
            'foto_identitas' => 'required|image|max:2048',
        ]);

        // Upload KTP
        $pathFoto = null;
        if ($request->hasFile('foto_identitas')) {
            $pathFoto = $request->file('foto_identitas')->store('identitas', 'public');
        }

        // Hitung Harga (Logika Hybrid)
        $hargaFrontend = (int) preg_replace('/[^0-9]/', '', $request->total_harga);
        
        $mobil = Mobil::findOrFail($request->mobil_id);
        $hargaMobil = (int) preg_replace('/[^0-9]/', '', (string)$mobil->harga);
        $start = \Carbon\Carbon::parse($request->tgl_ambil)->startOfDay();
        $end = \Carbon\Carbon::parse($request->tgl_kembali)->startOfDay();
        $durasi = $start->diffInDays($end) + 1;
        $hargaServer = $hargaMobil * $durasi;

        // Jika sopir dipilih, tambah biaya sopir
        $pakaiSopir = in_array($request->sopir, ['1', 'true', 'on', 'dengan_sopir']);
        if ($pakaiSopir) {
            $hargaServer += (150000 * $durasi);
        }

        // Prioritas harga dari frontend agar tidak nol
        $finalTotal = ($hargaFrontend > 0) ? $hargaFrontend : $hargaServer;

        // Simpan ke Database
        Transaksi::create([
            'user_id' => Auth::id(),
            'mobil_id' => $request->mobil_id,
            'nama' => Auth::user()->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_identitas' => $pathFoto,
            'tgl_ambil' => $request->tgl_ambil,
            'jam_ambil' => $request->jam_ambil,
            'tgl_kembali' => $request->tgl_kembali,
            'jam_kembali' => $request->jam_kembali,
            'tujuan' => $request->tujuan,
            'lokasi_ambil' => $request->lokasi_ambil,
            'lokasi_kembali' => $request->lokasi_kembali,
            'alamat_lengkap' => $request->alamat_lengkap ?? $request->alamat ?? '-',
            'alamat_jemput' => $request->alamat_jemput ?? $request->alamat ?? '-',
            'alamat_antar' => $request->alamat_antar ?? $request->alamat ?? '-',
            'sopir' => $pakaiSopir ? 'dengan_sopir' : 'lepas_kunci',
            'lama_sewa' => $durasi,
            'total_harga' => $finalTotal,
            'status' => 'Pending',
        ]);

        return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat!');
    }

    // 3. BATALKAN PESANAN
    public function batal($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        // Hanya boleh batal jika status masih Pending
        if ($transaksi->status == 'Pending' || $transaksi->status == null) {
            $transaksi->update(['status' => 'Dibatalkan']);
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    // 4. UPLOAD BUKTI BAYAR
    public function upload(Request $request, $id)
    {
        $request->validate(['bukti_bayar' => 'required|image|max:2048']);
        $transaksi = Transaksi::findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $transaksi->update(['bukti_bayar' => $path]);
        }
        return redirect()->back()->with('success', 'Bukti bayar berhasil diupload!');
    }

    // 5. CETAK TIKET (INI YANG TADI HILANG/EROR)
    public function cetak($id)
    {
        $transaksi = Transaksi::with(['user', 'mobil'])->findOrFail($id);

        // Validasi Pemilik: Hanya pemilik asli yang boleh cetak
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses Ditolak: Ini bukan tiket Anda.');
        }

        // Tampilkan halaman tiket
        return view('pages.tiket', compact('transaksi'));
    }
    
    public function create(Request $request)
    {
        // 1. Tangkap ID Mobil dari Link Chatbot (?mobil_id=...)
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            $selectedMobil = Mobil::find($request->mobil_id);
        }

        // 2. Ambil daftar semua mobil (untuk jaga-jaga jika user mau ganti pilihan)
        $mobils = Mobil::where('status', 'tersedia')->get();

        // 3. Arahkan ke Halaman Form (View)
        // Pastikan file view ini ada di: resources/views/user/transaksi/create.blade.php
        return view('user.transaksi.create', compact('selectedMobil', 'mobils'));
    }
}