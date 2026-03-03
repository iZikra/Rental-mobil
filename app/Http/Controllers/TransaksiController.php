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
        // 1. Validasi
        $request->validate([
            'mobil_id'       => 'required|exists:mobils,id',
            'no_hp'          => 'required|string|max:20',
            'alamat'         => 'required|string',
            'tgl_ambil'      => 'required|date',
            'jam_ambil'      => 'required',
            'tgl_kembali'    => 'required|date|after_or_equal:tgl_ambil',
            'jam_kembali'    => 'required',
            'foto_identitas' => 'required|image|max:2048', 
        ]);

        $waktuAmbil   = Carbon::parse($request->tgl_ambil . ' ' . $request->jam_ambil);
        $waktuKembali = Carbon::parse($request->tgl_kembali . ' ' . $request->jam_kembali);

        if ($waktuAmbil->lessThan(now()->subHour())) {
            return redirect()->back()->withInput()->with('error', 'Tanggal pengambilan tidak valid (sudah lewat).');
        }

        if ($waktuKembali->lessThanOrEqualTo($waktuAmbil)) {
            return redirect()->back()->withInput()->with('error', 'Waktu pengembalian harus setelah waktu pengambilan.');
        }

        // 2. Cek Bentrok (Menggunakan tgl_ambil)
        $cekBentrok = Transaksi::where('mobil_id', $request->mobil_id)
            ->whereNotIn('status', ['Dibatalkan', 'Ditolak', 'Selesai']) 
            ->where(function ($query) use ($request) {
                $query->where('tgl_ambil', '<=', $request->tgl_kembali)
                      ->where('tgl_kembali', '>=', $request->tgl_ambil);
            })->exists();

        if ($cekBentrok) {
            return redirect()->back()->withInput()->with('error', 'Maaf, unit mobil ini sudah dibooking pada rentang waktu tersebut.');
        }

        DB::beginTransaction();
        try {
            // 3. Upload KTP
            $pathFoto = $request->file('foto_identitas')->store('identitas', 'public');

            $mobil = Mobil::findOrFail($request->mobil_id);
            
            $selisihJam = $waktuAmbil->diffInHours($waktuKembali);
            $durasiHari = (int) ceil($selisihJam / 24);
            if ($durasiHari < 1) $durasiHari = 1;

            $biayaSewa = $mobil->harga_sewa * $durasiHari;
            $biayaSopir = ($request->sopir === 'dengan_sopir') ? (150000 * $durasiHari) : 0;
            $totalHarga = $biayaSewa + $biayaSopir;

            // 4. Simpan Data Transaksi Sesuai Rancangan Asli Anda
            Transaksi::create([
                'user_id'         => Auth::id(),
                'mobil_id'        => $mobil->id,
                'rental_id'       => $mobil->rental_id, // WAJIB ADA
                'branch_id'       => $mobil->branch_id,
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
                'sopir'           => $request->sopir ?? 'tanpa_sopir',
                'lama_sewa'       => $durasiHari,
                'total_harga'     => $totalHarga,
                'status'          => 'Pending',
            ]);

            DB::commit(); 
            return redirect()->route('riwayat')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error("Error Store Transaksi User " . Auth::id() . ": " . $e->getMessage());
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
        $request->validate([
            'bukti_bayar' => 'required|image|max:4096' 
        ]);

        $transaksi = Transaksi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($transaksi->bukti_bayar) {
            Storage::disk('public')->delete($transaksi->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        
        $transaksi->update([
            'bukti_bayar' => $path,
            'status'      => 'dibayar' // Menggunakan ENUM yang sah dari database, BUKAN 'Menunggu Konfirmasi'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Tunggu konfirmasi dari Vendor.');
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