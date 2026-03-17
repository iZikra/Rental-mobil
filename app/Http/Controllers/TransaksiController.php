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

    public function store(Request $request)
{
    // 1. Validasi Input KTP dan SIM
    $request->validate([
        'mobil_id'       => 'required|exists:mobils,id',
        'no_hp'          => 'required|string|max:20',
        'alamat'         => 'required|string',
        'tgl_ambil'      => 'required|date',
        'jam_ambil'      => 'required',
        'tgl_kembali'    => 'required|date|after_or_equal:tgl_ambil',
        'jam_kembali'    => 'required',
        'foto_identitas' => 'required|image|max:2048',
        'foto_sim'       => 'required|image|mimes:jpeg,png,jpg|max:2048', 
    ]);

    // 2. Validasi Waktu
    $waktuAmbil   = \Carbon\Carbon::parse($request->tgl_ambil . ' ' . $request->jam_ambil);
    $waktuKembali = \Carbon\Carbon::parse($request->tgl_kembali . ' ' . $request->jam_kembali);

    if ($waktuAmbil->lessThan(now()->subHour())) {
        return redirect()->back()->withInput()->with('error', 'Tanggal pengambilan tidak valid (sudah lewat).');
    }

    if ($waktuKembali->lessThanOrEqualTo($waktuAmbil)) {
        return redirect()->back()->withInput()->with('error', 'Waktu pengembalian harus setelah waktu pengambilan.');
    }

    // 3. Cek Bentrok Jadwal
    $cekBentrok = \App\Models\Transaksi::where('mobil_id', $request->mobil_id)
        ->whereNotIn('status', ['Dibatalkan', 'Ditolak', 'Selesai']) 
        ->where(function ($query) use ($request) {
            $query->where('tgl_ambil', '<=', $request->tgl_kembali)
                  ->where('tgl_kembali', '>=', $request->tgl_ambil);
        })->exists();

    if ($cekBentrok) {
        return redirect()->back()->withInput()->with('error', 'Maaf, unit mobil ini sudah dibooking pada rentang waktu tersebut.');
    }

    \Illuminate\Support\Facades\DB::beginTransaction();
    try {
        // 4. Upload KTP & SIM (Dilakukan di dalam Try-Catch agar aman)
        $pathFotoKtp = $request->file('foto_identitas')->store('identitas', 'public');
        $pathFotoSim = $request->file('foto_sim')->store('sim_pelanggan', 'public');

        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        
        // 5. Kalkulasi Harga dan Durasi
        $selisihJam = $waktuAmbil->diffInHours($waktuKembali);
        $durasiHari = (int) ceil($selisihJam / 24);
        if ($durasiHari < 1) $durasiHari = 1;

        $biayaSewa = $mobil->harga_sewa * $durasiHari;
        $biayaSopir = ($request->sopir === 'dengan_sopir') ? (150000 * $durasiHari) : 0;
        $totalHarga = $biayaSewa + $biayaSopir;

        // 6. Eksekusi Simpan Data Utama (Ditambah atribut foto_sim)
        \App\Models\Transaksi::create([
            'user_id'         => \Illuminate\Support\Facades\Auth::id(),
            'mobil_id'        => $mobil->id,
            'rental_id'       => $mobil->rental_id, // WAJIB ADA
            'branch_id'       => $mobil->branch_id,
            'nama'            => \Illuminate\Support\Facades\Auth::user()->name,
            'no_hp'           => $request->no_hp,
            'alamat'          => $request->alamat,
            'foto_identitas'  => $pathFotoKtp,
            'foto_sim'        => $pathFotoSim, // INI PENYELAMATNYA
            'tgl_ambil'       => $request->tgl_ambil,
            'jam_ambil'       => $request->jam_ambil,
            'tgl_kembali'     => $request->tgl_kembali,
            'jam_kembali'     => $request->jam_kembali,
            'tujuan'          => $request->tujuan,
            'lokasi_ambil'    => $request->lokasi_ambil,
            'lokasi_kembali'  => $request->lokasi_kembali,
            'alamat_jemput'   => $request->lokasi_ambil == 'lainnya' ? $request->alamat_lengkap : 'Ambil di Kantor',
            'alamat_antar'    => $request->lokasi_kembali == 'lainnya' ? ($request->alamat_antar_manual ?? $request->alamat) : 'Kembalikan ke Kantor',
            'sopir'           => $request->sopir ?? 'tanpa_sopir',
            'lama_sewa'       => $durasiHari,
            'total_harga'     => $totalHarga,
            'status'          => 'Pending',
        ]);

        \Illuminate\Support\Facades\DB::commit(); 
        
        return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\DB::rollBack(); 
        \Illuminate\Support\Facades\Log::error("Error Store Transaksi User " . \Illuminate\Support\Facades\Auth::id() . ": " . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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
        // 1. Validasi Input yang lebih ketat (batasi tipe file)
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:4096' 
        ]);

        // 2. Cari transaksi milik user yang login
        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 3. KEAMANAN: Jangan izinkan upload jika status sudah diproses vendor
        if (in_array($transaksi->status, ['Dikonfirmasi', 'Selesai', 'Ditolak'])) {
            return redirect()->back()->with('error', 'Transaksi sudah diproses, tidak dapat mengunggah ulang bukti.');
        }

        // 4. Hapus bukti lama dari storage jika user melakukan upload ulang (mencegah sampah file)
        if ($transaksi->bukti_bayar && Storage::disk('public')->exists($transaksi->bukti_bayar)) {
            Storage::disk('public')->delete($transaksi->bukti_bayar);
        }

        // 5. Simpan file baru
        $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
        
        // 6. Update Database
        $transaksi->update([
            'bukti_bayar' => $path,
            'status'      => 'dibayar' 
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Tunggu konfirmasi dari Vendor.');
    }

    /**
     * ADMIN ONLY: Menyelesaikan Transaksi (Pengembalian Mobil).
     * Pastikan route ini dilindungi middleware 'admin'.
     */
    public function selesaikanTransaksi($id)
    {
        $user = Auth::user();

        // 1. Ambil transaksi dengan relasi mobil agar tahu ini milik cabang mana
        $transaksi = Transaksi::with('mobil')->findOrFail($id);

        // 2. FILTER KEAMANAN (Authorization)
        // Jika bukan Super Admin, maka harus dicek apakah branch_id-nya cocok
        if ($user->role !== 'admin') { 
            // Cek apakah user memiliki branch_id yang sama dengan mobil di transaksi tersebut
            if ($user->branch_id !== $transaksi->branch_id) {
                Log::warning("User ID {$user->id} mencoba akses ilegal ke Transaksi ID {$id}");
                return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki akses untuk menyelesaikan transaksi di cabang ini!');
            }
        }

        DB::beginTransaction();
        try {
            // 3. Update status transaksi menjadi 'Selesai'
            $transaksi->update(['status' => 'Selesai']);

            // 4. PENTING: Update status mobil di tabel mobils menjadi 'tersedia'
            // Menggunakan Eloquent agar observer (jika ada) bisa menangkap perubahannya
            $mobil = Mobil::find($transaksi->mobil_id);
            if ($mobil) {
                $mobil->update(['status' => 'tersedia']);
            }

            DB::commit();

            // Log untuk audit
            Log::info("Transaksi ID {$id} diselesaikan oleh {$user->name}");

            return redirect()->back()->with('success', 'Transaksi Selesai. Unit mobil kembali tersedia di stok Chatbot.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Selesaikan Transaksi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyelesaikan transaksi: ' . $e->getMessage());
        }
    }
    /**
     * Cetak Tiket/Invoice.
     */
public function cetak($id)
{
    $transaksi = Transaksi::with('mobil')->findOrFail($id);

    return view('pages.cetak_tiket', compact('transaksi'));
}
}