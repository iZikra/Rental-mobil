<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    /**
     * Menampilkan riwayat transaksi milik user.
     */
    public function index()
    {
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->with('mobil')
            ->latest()
            ->get();

        return view('pages.riwayat', compact('transaksis'));
    }

    /**
     * Form pembuatan pesanan baru.
     */
    public function create(Request $request)
    {
        $selectedMobil = null;
        if ($request->has('mobil_id')) {
            $selectedMobil = Mobil::find($request->mobil_id);
        }
        // Hanya tampilkan mobil yang statusnya 'tersedia'
        $semuaMobil = Mobil::where('status', 'tersedia')->get();
        
        return view('pages.order', compact('selectedMobil', 'semuaMobil'));
    }

    /**
     * LOGIKA UTAMA: Menyimpan transaksi baru.
     * Perbaikan: Menggunakan perhitungan durasi per 24 Jam (Ceiling).
     */
    public function store(Request $request)
    {
        $request->validate([
            'mobil_id'     => 'required|exists:mobils,id',
            'no_hp'        => 'required|string|max:20',
            'alamat'       => 'required|string',
            'tgl_ambil'    => 'required|date',
            'jam_ambil'    => 'required',
            'tgl_kembali'  => 'required|date|after_or_equal:tgl_ambil',
            'jam_kembali'  => 'required',
            'foto_identitas' => 'required|image|max:2048', // Maks 2MB
        ]);

        // 1. Gabungkan Tanggal & Jam untuk presisi waktu
        $waktuAmbil   = Carbon::parse($request->tgl_ambil . ' ' . $request->jam_ambil);
        $waktuKembali = Carbon::parse($request->tgl_kembali . ' ' . $request->jam_kembali);

        // Validasi Logika Waktu: Waktu ambil tidak boleh di masa lalu (beri toleransi 1 jam untuk delay input)
        if ($waktuAmbil->lessThan(now()->subHour())) {
            return redirect()->back()->withInput()->with('error', 'Tanggal pengambilan tidak valid (sudah lewat).');
        }

        // Validasi Logika Waktu: Waktu kembali harus SETELAH waktu ambil
        if ($waktuKembali->lessThanOrEqualTo($waktuAmbil)) {
            return redirect()->back()->withInput()->with('error', 'Waktu pengembalian harus setelah waktu pengambilan.');
        }

        // 2. Cek Bentrok (Overlapping Booking)
        // Mencegah mobil yang sama dipesan di rentang waktu yang beririsan
        $cekBentrok = Transaksi::where('mobil_id', $request->mobil_id)
            ->whereNotIn('status', ['Dibatalkan', 'Ditolak', 'Selesai']) // Abaikan status yang sudah tidak aktif
            ->where(function ($query) use ($request) {
                $query->where('tgl_ambil', '<=', $request->tgl_kembali)
                      ->where('tgl_kembali', '>=', $request->tgl_ambil);
            })->exists();

        if ($cekBentrok) {
            return redirect()->back()->withInput()->with('error', 'Maaf, unit mobil ini sudah dibooking pada tanggal tersebut.');
        }

        // MULAI DATABASE TRANSACTION
        // Jika satu proses gagal, semua dibatalkan (Rollback)
        DB::beginTransaction();
        try {
            // 3. Upload KTP
            $pathFoto = $request->file('foto_identitas')->store('identitas', 'public');

            // 4. PERBAIKAN LOGIKA HITUNG DURASI & HARGA
            $mobil = Mobil::findOrFail($request->mobil_id);
            
            // Hitung selisih dalam JAM
            $selisihJam = $waktuAmbil->diffInHours($waktuKembali);
            
            // Rumus Bisnis: Bagi 24, lalu bulatkan ke atas (Ceil)
            // Contoh: 25 jam = 2 hari. 48 jam = 2 hari.
            $durasiHari = (int) ceil($selisihJam / 24);
            
            // Minimal sewa 1 hari meski cuma 1 jam
            if ($durasiHari < 1) $durasiHari = 1;

            // Hitung Biaya
            $biayaSewa = $mobil->harga_sewa * $durasiHari;
            $biayaSopir = 0;

            if ($request->sopir === 'dengan_sopir') { 
                $biayaSopir = (150000 * $durasiHari); 
            }
            
            $totalHarga = $biayaSewa + $biayaSopir;

            // 5. Simpan Data Transaksi
            Transaksi::create([
                'user_id'         => Auth::id(),
                'mobil_id'        => $request->mobil_id,
                'nama'            => Auth::user()->name,
                'no_hp'           => $request->no_hp,
                'alamat'          => $request->alamat,
                'foto_identitas'  => $pathFoto,
                'tgl_ambil'       => $request->tgl_ambil,
                'jam_ambil'       => $request->jam_ambil,
                'tgl_kembali'     => $request->tgl_kembali,
                'jam_kembali'     => $request->jam_kembali,
                'tujuan'          => $request->tujuan,
                'lokasi_ambil'    => $request->lokasi_ambil,
                'lokasi_kembali'  => $request->lokasi_kembali,
                'alamat_jemput'   => $request->lokasi_ambil == 'lainnya' ? $request->alamat_lengkap : 'Ambil di Kantor',
                'alamat_antar'    => $request->lokasi_kembali == 'lainnya' ? ($request->alamat_antar_manual ?? $request->alamat) : 'Kembalikan ke Kantor',
                'sopir'           => $request->sopir ?? 'lepas_kunci',
                'lama_sewa'       => $durasiHari, // Menggunakan hasil perhitungan baru
                'total_harga'     => $totalHarga,
                'status'          => 'Pending', // Status awal
            ]);

            // 6. Update Status Mobil menjadi 'disewa' agar tidak muncul di pencarian user lain
            // FORCE UPDATE via Query Builder agar lebih cepat & pasti
            DB::table('mobils')->where('id', $request->mobil_id)->update(['status' => 'disewa']);

            DB::commit(); // Simpan permanen
            return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika error
            Log::error("Error Store Transaksi User " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan.');
        }
    }

    /**
     * User Membatalkan Pesanan.
     * Mengembalikan status mobil menjadi 'tersedia'.
     */
    public function batalkanPesanan($id)
    {
        // Pastikan transaksi milik user yang sedang login
        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau akses ditolak.');
        }

        // Jangan izinkan pembatalan jika sudah selesai/sedang jalan
        if (in_array($transaksi->status, ['Sedang Jalan', 'Selesai'])) {
            return redirect()->back()->with('error', 'Pesanan yang sedang berjalan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Update status transaksi
            $transaksi->update(['status' => 'Dibatalkan']);

            // Kembalikan status mobil jadi tersedia
            DB::table('mobils')->where('id', $transaksi->mobil_id)->update(['status' => 'tersedia']);

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan. Dana akan dikembalikan sesuai kebijakan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Batal Transaksi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    /**
     * User Upload Bukti Pembayaran.
     */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:4096' // Max 4MB
        ]);

        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hapus bukti lama jika ada (untuk hemat storage)
        if ($transaksi->bukti_bayar) {
            Storage::disk('public')->delete($transaksi->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        
        $transaksi->update([
            'bukti_bayar' => $path,
            'status'      => 'Menunggu Konfirmasi' // Update status agar admin mendapat notifikasi
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Tunggu konfirmasi Admin.');
    }

    /**
     * ADMIN ONLY: Menyelesaikan Transaksi (Pengembalian Mobil).
     * Pastikan route ini dilindungi middleware 'admin'.
     */
    public function selesaikanTransaksi($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            // Ubah status transaksi
            $transaksi->update(['status' => 'Selesai']);

            // PENTING: Mobil harus kembali 'tersedia' saat transaksi selesai
            DB::table('mobils')->where('id', $transaksi->mobil_id)->update(['status' => 'tersedia']);

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi Selesai. Unit mobil kembali tersedia.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyelesaikan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Tiket/Invoice.
     */
    public function cetak($id)
    {
        $transaksi = Transaksi::with(['user', 'mobil'])->findOrFail($id);
        
        // Keamanan: Hanya pemilik transaksi atau admin yang boleh lihat
        if ($transaksi->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        return view('pages.tiket', compact('transaksi'));
    }
}