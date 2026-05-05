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
            ->whereIn('status', ['Draft', 'Pending'])
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
            ->whereIn('status', ['Draft', 'Pending'])
            ->first();

        if (!$transaksi) {
            return redirect()->route('home')->with('error', 'Link pesanan tidak valid atau sudah kadaluwarsa.');
        }

        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'telp_customer' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'jam_mulai_jam' => 'required',
            'jam_mulai_menit' => 'required',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai_jam' => 'required',
            'jam_selesai_menit' => 'required',
            'foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tipe_pengambilan' => 'required|string',
            'tipe_pengembalian' => 'required|string',
        ]);

        $car = Mobil::find($transaksi->mobil_id);
        
        // Handle file uploads
        $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
        $fotoSimPath = $request->file('foto_sim')->store('sim', 'public');

        $awal = Carbon::parse($request->tanggal_mulai);
        $akhir = Carbon::parse($request->tanggal_selesai);
        $lama_sewa = max($awal->diffInDays($akhir), 1);
        
        $jam_mulai = str_pad($request->jam_mulai_jam, 2, '0', STR_PAD_LEFT) . ':' . str_pad($request->jam_mulai_menit, 2, '0', STR_PAD_LEFT);
        $jam_selesai = str_pad($request->jam_selesai_jam, 2, '0', STR_PAD_LEFT) . ':' . str_pad($request->jam_selesai_menit, 2, '0', STR_PAD_LEFT);

        // Kalkulasi biaya layanan (menggunakan biaya_bandara_per_trip sebagai base tarif antar/jemput)
        $rental = $car->rental;
        $biayaLayanan = (int) ($rental->biaya_bandara_per_trip ?? 0);
        
        $biayaTambahan = 0;
        if ($request->tipe_pengambilan === 'lainnya') $biayaTambahan += $biayaLayanan;
        if ($request->tipe_pengembalian === 'lainnya') $biayaTambahan += $biayaLayanan;

        $total_harga = ($lama_sewa * $car->harga_sewa) + $biayaTambahan;

        // Update record transaksi  
        $transaksi->update([
            'nama' => $request->nama_customer,
            'no_hp' => $request->telp_customer,
            'alamat' => '-',
            'foto_identitas' => $fotoIdentitasPath,
            'foto_sim' => $fotoSimPath,
            'tgl_ambil' => $request->tanggal_mulai,
            'jam_ambil' => $jam_mulai,
            'tgl_kembali' => $request->tanggal_selesai,
            'jam_kembali' => $jam_selesai,
            'lokasi_ambil' => $request->tipe_pengambilan === 'lainnya' ? $request->alamat_pengambilan : 'Kantor Rental',
            'lokasi_kembali' => $request->tipe_pengembalian === 'lainnya' ? $request->alamat_pengembalian : 'Kantor Rental',
            'alamat_antar' => $request->tipe_pengambilan === 'lainnya' ? $request->alamat_pengambilan : null,
            'alamat_jemput' => $request->tipe_pengembalian === 'lainnya' ? $request->alamat_pengembalian : null,
            'lama_sewa' => $lama_sewa,
            'biaya_tambahan' => $biayaTambahan,
            'total_harga' => $total_harga,
            'status' => 'Pending', // Ubah status dari 'Draft' ke 'Pending' agar muncul di dashboard mitra
            'booking_token' => null, // Hanguskan link agar tidak bisa digunakan lagi (Lebih Aman)
            'token_expires_at' => null,
            'catatan' => 'Pemesanan Guest via AI Bot',
        ]);

        // Cek jika ada redirect sistem pembayaran midtrans, jika tidak redirect success pesan berhasil
        return redirect()->route('home')->with('success', 'Pesanan Anda telah diterima dan diteruskan ke mitra. Tim kami mungkin akan menghubungi nomor ' . $request->telp_customer);
    }
}
