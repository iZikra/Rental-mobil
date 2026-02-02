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
    // GANTI METHOD store() DENGAN KODE INI
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'mobil_id' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'tgl_ambil' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_ambil',
            'foto_identitas' => 'required|image|max:2048',
        ]);

        // =========================================================================
        // 2. LOGIKA ANTI-BENTROK (OVERBOOKING PROTECTION) [BARU]
        // =========================================================================
        $tglAmbilBaru = $request->tgl_ambil;
        $tglKembaliBaru = $request->tgl_kembali;

        $cekBentrok = Transaksi::where('mobil_id', $request->mobil_id)
            // Abaikan status yang sudah tidak aktif
            ->whereNotIn('status', ['Dibatalkan', 'Ditolak', 'Selesai']) 
            // Cek tumpang tindih tanggal
            ->where(function ($query) use ($tglAmbilBaru, $tglKembaliBaru) {
                $query->where('tgl_ambil', '<=', $tglKembaliBaru)
                      ->where('tgl_kembali', '>=', $tglAmbilBaru);
            })
            ->exists(); // Mengembalikan true jika ada yang bentrok

        if ($cekBentrok) {
            // Kembalikan user dengan pesan error
            return redirect()->back()
                ->withInput() // Kembalikan isian form agar user tidak mengetik ulang
                ->with('error', 'Maaf, mobil ini SUDAH DIBOOKING pada tanggal tersebut. Silakan pilih tanggal lain atau mobil lain.');
        }
        // =========================================================================


        // 3. Upload KTP
        $pathFoto = null;
        if ($request->hasFile('foto_identitas')) {
            $pathFoto = $request->file('foto_identitas')->store('identitas', 'public');
        }

        // 4. Hitung Harga (Logika Hybrid)
        $hargaFrontend = (int) preg_replace('/[^0-9]/', '', $request->total_harga);
        
        $mobil = Mobil::findOrFail($request->mobil_id);
        $hargaMobil = (int) preg_replace('/[^0-9]/', '', (string)$mobil->harga);
        $start = \Carbon\Carbon::parse($request->tgl_ambil)->startOfDay();
        $end = \Carbon\Carbon::parse($request->tgl_kembali)->startOfDay();
        $durasi = $start->diffInDays($end) + 1;
        $hargaServer = $hargaMobil * $durasi;

        // Jika sopir dipilih
        $pakaiSopir = in_array($request->sopir, ['1', 'true', 'on', 'dengan_sopir']);
        if ($pakaiSopir) {
            $hargaServer += (150000 * $durasi);
        }

        // Prioritas harga
        $finalTotal = ($hargaFrontend > 0) ? $hargaFrontend : $hargaServer;

        // 5. Simpan ke Database
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
            // Status awal bisa Pending atau Menunggu Pembayaran
            'status' => 'Pending', 
        ]);

        return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat! Segera lakukan pembayaran.');
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
        // 1. Validasi Ekstra (Mencegah file berbahaya selain gambar)
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:4096' // Max 4MB
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // 2. Security Check: Pastikan hanya transaksi Pending yang bisa diupload
        // Mencegah user mengubah bukti bayar pada transaksi yang sudah Selesai/Dibatalkan
        if (!in_array($transaksi->status, ['Pending', 'Menunggu Pembayaran'])) {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses, tidak bisa upload ulang.');
        }

        if ($request->hasFile('bukti_bayar')) {
            // 3. Garbage Collection: Hapus bukti bayar lama jika user re-upload (Menghemat Storage)
            if ($transaksi->bukti_bayar) {
                Storage::disk('public')->delete($transaksi->bukti_bayar);
            }

            // Simpan file baru
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

            // 4. Update Database & STATUS (PENTING!)
            // Status harus berubah agar Admin mendapat sinyal untuk memvalidasi
            $transaksi->update([
                'bukti_bayar' => $path,
                'status'      => 'Menunggu Konfirmasi' 
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran diterima! Mohon tunggu verifikasi admin.');
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
        // 1. Tangkap ID Mobil
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            $selectedMobil = Mobil::find($request->mobil_id);
        }

        // 2. Ambil semua mobil
        $semuaMobil = Mobil::where('status', 'tersedia')->get(); // Ubah nama variabel jadi $semuaMobil

        // 3. Arahkan ke View yang benar
        // Pastikan mengarah ke 'pages.order', bukan 'user.transaksi.create'
        return view('pages.order', compact('selectedMobil', 'semuaMobil'));
    }
    
}