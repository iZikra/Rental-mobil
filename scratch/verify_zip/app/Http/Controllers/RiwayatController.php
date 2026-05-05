<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
{
    // Ambil data transaksi milik user yang sedang login
    $transaksi = Transaksi::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->get();

    // PENTING: Kirim variabel 'transaksi' ke view
    return view('pages.riwayat', compact('transaksi'));
}
    public function cetak($id)
    {
        // 1. Cari transaksi berdasarkan ID
        $transaksi = Transaksi::with('mobil')->findOrFail($id);

        // 2. Keamanan: Pastikan yang mencetak adalah pemilik transaksi
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Anda tidak berhak mencetak tiket ini.');
        }

        // 3. Tampilkan halaman tiket (print friendly)
        return view('pages.cetak_tiket', compact('transaksi'));
    }
    public function cancel($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // 1. Cek Keamanan: Apakah ini punya user yang login?
        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // 2. Cek Status: Hanya boleh cancel jika masih pending
        if ($transaksi->status != 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses/selesai.');
        }

        // 3. Ubah status jadi 'canceled' (Dibatalkan)
        // Kita gunakan istilah 'canceled' agar beda dengan 'rejected' (ditolak admin)
        $transaksi->update(['status' => 'canceled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
    // Pastikan import ini ada di paling atas file
    // use Illuminate\Support\Facades\Storage; 

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            // 1. Hapus foto lama jika ada (agar hemat memori)
            if ($transaksi->bukti_bayar && Storage::disk('public')->exists($transaksi->bukti_bayar)) {
                Storage::disk('public')->delete($transaksi->bukti_bayar);
            }

            // 2. Simpan Foto Baru (Gunakan Hash Name agar aman)
            // Ini akan menghasilkan: "bukti_bayar/somerandomstring.png"
            // File tersimpan fisik di: storage/app/public/bukti_bayar/
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

            // 3. Simpan Path ke Database
            $transaksi->update([
                'bukti_bayar' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }
}