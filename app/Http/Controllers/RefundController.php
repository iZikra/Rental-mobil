<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    /**
     * User mengajukan refund
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id'   => 'required|exists:transaksis,id',
            'alasan_refund'  => 'required|string|max:1000',
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik'   => 'required|string|max:150',
        ]);

        $transaksi = Transaksi::where('id', $request->transaksi_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cek apakah status transaksi saat ini Disewa (sudah dibayar)
        if ($transaksi->status !== 'Disewa') {
            return redirect()->back()->with('error', 'Refund hanya bisa diajukan untuk transaksi yang sudah dibayar.');
        }

        // Cek apakah waktu sewa sudah dimulai
        $waktuAmbil = \Carbon\Carbon::parse($transaksi->tgl_ambil . ' ' . $transaksi->jam_ambil);
        if (now()->greaterThanOrEqualTo($waktuAmbil)) {
            return redirect()->back()->with('error', 'Pengajuan refund ditolak karena masa sewa sudah dimulai.');
        }

        // Cek apakah sudah pernah mengajukan refund
        $existingRefund = Refund::where('transaksi_id', $transaksi->id)->first();
        if ($existingRefund && $existingRefund->status === 'menunggu') {
            return redirect()->back()->with('error', 'Anda sudah mengajukan refund untuk transaksi ini.');
        }

        DB::beginTransaction();
        try {
            // Buat data refund
            Refund::create([
                'transaksi_id'   => $transaksi->id,
                'jumlah_refund'  => $transaksi->total_harga, // Refund full amount
                'alasan_refund'  => $request->alasan_refund,
                'nama_bank'      => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'nama_pemilik'   => $request->nama_pemilik,
                'status'         => 'menunggu',
            ]);

            // Update status transaksi menjadi Menunggu Refund
            $transaksi->update(['status' => 'Menunggu Refund']);

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan refund berhasil dikirim. Menunggu konfirmasi admin/mitra.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Mengajukan Refund: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan refund.');
        }
    }

    /**
     * Mitra/Admin menyetujui refund
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $refund = Refund::with('transaksi')->findOrFail($id);

        if ($refund->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Refund ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $path = $request->file('bukti_transfer')->store('bukti_refund', 'public');

            $refund->update([
                'bukti_transfer' => $path,
                'status'         => 'disetujui',
            ]);

            // Update status transaksi menjadi Dibatalkan
            $refund->transaksi->update(['status' => 'Dibatalkan']);

            DB::commit();
            return redirect()->back()->with('success', 'Refund berhasil disetujui dan transaksi telah dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Menyetujui Refund: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses refund.');
        }
    }

    /**
     * Mitra/Admin menolak refund
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $refund = Refund::with('transaksi')->findOrFail($id);

        if ($refund->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Refund ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $refund->update([
                'status'           => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);

            // Kembalikan status transaksi menjadi Disewa
            $refund->transaksi->update(['status' => 'Disewa']);

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan refund telah ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal Menolak Refund: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses penolakan.');
        }
    }
}
