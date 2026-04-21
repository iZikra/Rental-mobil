<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Mobil;
use Carbon\Carbon;

class GuestBookingController extends Controller
{
    public function showForm($token)
    {
        // Cari transaksi sementara berdasarkan token
        $transaksi = Transaksi::where('booking_token', $token)
            ->where('token_expires_at', '>', now())
            ->where('status', 'Pending')
            ->first();

        if (!$transaksi) {
            return redirect()->route('home')->with('error', 'Link pesanan tidak valid atau sudah kadaluwarsa.');
        }

        $car = Mobil::find($transaksi->mobil_id);
        
        return view('frontend.guest_booking', [
            'transaksi' => $transaksi,
            'car' => $car
        ]);
    }

    public function submitForm(Request $request, $token)
    {
        $transaksi = Transaksi::where('booking_token', $token)
            ->where('token_expires_at', '>', now())
            ->where('status', 'Pending')
            ->first();

        if (!$transaksi) {
            return redirect()->route('home')->with('error', 'Link pesanan tidak valid atau sudah kadaluwarsa.');
        }

        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'telp_customer' => 'required|string|max:20',
            'alamat_customer' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'jam_mulai' => 'required',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai' => 'required',
            'foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tipe_pengambilan' => 'required|string',
            'tipe_pengembalian' => 'required|string',
        ]);

        $car = Mobil::find($transaksi->mobil_id);
        
        // Handle file uploads
        $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
        $fotoSimPath = $request->file('foto_sim')->store('sim', 'public');

        // Kalkulasi ulang harga
        $awal = Carbon::parse($request->tanggal_mulai);
        $akhir = Carbon::parse($request->tanggal_selesai);
        $lama_sewa = max($awal->diffInDays($akhir), 1);
        $total_harga = $lama_sewa * $car->harga_sewa;

        // Update record transaksi  
        $transaksi->update([
            'nama' => $request->nama_customer,
            'no_hp' => $request->telp_customer,
            'alamat' => $request->alamat_customer,
            'foto_identitas' => $fotoIdentitasPath,
            'foto_sim' => $fotoSimPath,
            'tgl_ambil' => $request->tanggal_mulai,
            'jam_ambil' => $request->jam_mulai,
            'tgl_kembali' => $request->tanggal_selesai,
            'jam_kembali' => $request->jam_selesai,
            'lokasi_ambil' => $request->tipe_pengambilan === 'lainnya' ? $request->alamat_pengambilan : 'Kantor Rental',
            'lokasi_kembali' => $request->tipe_pengembalian === 'lainnya' ? $request->alamat_pengembalian : 'Kantor Rental',
            'lama_sewa' => $lama_sewa,
            'total_harga' => $total_harga,
            'status' => 'Pending', // Status tetap pending namun siap dibayar/diverifikasi
            'booking_token' => null, // Hanguskan link
            'token_expires_at' => null,
            'catatan' => 'Pemesanan Guest via AI Bot',
        ]);

        // Cek jika ada redirect sistem pembayaran midtrans, jika tidak redirect success pesan berhasil
        return redirect()->route('home')->with('success', 'Pesanan Anda telah diterima dan diteruskan ke mitra. Tim kami mungkin akan menghubungi nomor ' . $request->telp_customer);
    }
}
