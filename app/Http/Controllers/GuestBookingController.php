<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Mobil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GuestBookingController extends Controller
{
    public function showForm($rental_id, $token)
    {
        $transaksi = Transaksi::where('booking_token', $token)
            ->whereIn('status', ['Draft', 'Pending'])
            ->first();

        if (!$transaksi) {
            Log::warning("GuestBooking: Transaksi tidak ditemukan untuk token {$token}");
            return redirect()->route('home')->with('error', 'Link pesanan tidak valid.');
        }

        if (Carbon::parse($transaksi->token_expires_at)->isPast()) {
            Log::warning("GuestBooking: Token {$token} sudah kadaluwarsa.");
            return redirect()->route('home')->with('error', 'Link pesanan sudah kadaluwarsa.');
        }

        if ($transaksi->rental_id != $rental_id) {
            Log::warning("GuestBooking: Rental ID mismatch. (URL: {$rental_id}, DB: {$transaksi->rental_id})");
            return redirect()->route('home')->with('error', 'Token tidak valid untuk mitra rental ini.');
        }

        $car = Mobil::find($transaksi->mobil_id);

        return view('frontend.guest_booking', [
            'transaksi' => $transaksi,
            'car'       => $car,
            'rental_id' => $rental_id,
        ]);
    }

    public function submitForm(Request $request, $rental_id, $token)
    {
        $transaksi = Transaksi::where('booking_token', $token)
            ->whereIn('status', ['Draft', 'Pending'])
            ->first();

        if (!$transaksi) {
            return redirect()->route('home')->with('error', 'Link pesanan tidak valid.');
        }

        if (Carbon::parse($transaksi->token_expires_at)->isPast()) {
            return redirect()->route('home')->with('error', 'Link pesanan sudah kadaluwarsa.');
        }

        if ($transaksi->rental_id != $rental_id) {
            return redirect()->route('home')->with('error', 'Token tidak valid untuk mitra rental ini.');
        }

        $request->validate([
            'nama_customer'     => 'required|string|max:255',
            'telp_customer'     => 'required|string|max:20',
            'tanggal_mulai'     => 'required|date',
            'jam_mulai_jam'     => 'required',
            'jam_mulai_menit'   => 'required',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai_jam'   => 'required',
            'jam_selesai_menit' => 'required',
            'foto_identitas'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sim'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tipe_pengambilan'  => 'required|string',
            'tipe_pengembalian' => 'required|string',
        ]);

        $car = Mobil::find($transaksi->mobil_id);

        // Upload dokumen
        $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
        $fotoSimPath       = $request->file('foto_sim')->store('sim', 'public');

        $awal      = Carbon::parse($request->tanggal_mulai);
        $akhir     = Carbon::parse($request->tanggal_selesai);
        $lama_sewa = max($awal->diffInDays($akhir), 1);

        $jam_mulai   = str_pad($request->jam_mulai_jam, 2, '0', STR_PAD_LEFT) . ':' . str_pad($request->jam_mulai_menit, 2, '0', STR_PAD_LEFT);
        $jam_selesai = str_pad($request->jam_selesai_jam, 2, '0', STR_PAD_LEFT) . ':' . str_pad($request->jam_selesai_menit, 2, '0', STR_PAD_LEFT);

        $rental        = $car->rental;
        $biayaLayanan  = (int) ($rental->biaya_bandara_per_trip ?? 0);
        $biayaTambahan = 0;
        if ($request->tipe_pengambilan === 'lainnya')  $biayaTambahan += $biayaLayanan;
        if ($request->tipe_pengembalian === 'lainnya') $biayaTambahan += $biayaLayanan;

        $total_harga = ($lama_sewa * $car->harga_sewa) + $biayaTambahan;

        // Update transaksi
        $transaksi->update([
            'nama'             => $request->nama_customer,
            'no_hp'            => $request->telp_customer,
            'alamat'           => '-',
            'foto_identitas'   => $fotoIdentitasPath,
            'foto_sim'         => $fotoSimPath,
            'tgl_ambil'        => $request->tanggal_mulai,
            'jam_ambil'        => $jam_mulai,
            'tgl_kembali'      => $request->tanggal_selesai,
            'jam_kembali'      => $jam_selesai,
            'lokasi_ambil'     => $request->tipe_pengambilan === 'lainnya' ? $request->alamat_pengambilan : 'Kantor Rental',
            'lokasi_kembali'   => $request->tipe_pengembalian === 'lainnya' ? $request->alamat_pengembalian : 'Kantor Rental',
            'alamat_antar'     => $request->tipe_pengambilan === 'lainnya' ? $request->alamat_pengambilan : null,
            'alamat_jemput'    => $request->tipe_pengembalian === 'lainnya' ? $request->alamat_pengembalian : null,
            'lama_sewa'        => $lama_sewa,
            'biaya_tambahan'   => $biayaTambahan,
            'total_harga'      => $total_harga,
            'status'           => 'Pending',
            'booking_token'    => null,
            'token_expires_at' => null,
            'catatan'          => 'Pemesanan Guest via AI Bot',
        ]);

        // --- WA KE CUSTOMER ---
        try {
            $noHpCustomer = $request->telp_customer;
            if (!empty($noHpCustomer)) {
                $infoRekening = "Tim kami akan segera menghubungi Anda untuk informasi pembayaran.";
                if ($rental && !empty($rental->no_rekening)) {
                    $infoRekening = "*Informasi Pembayaran:*\n"
                                  . "Bank      : {$rental->nama_bank}\n"
                                  . "Rekening  : {$rental->no_rekening}\n"
                                  . "Atas Nama : {$rental->atas_nama_rekening}\n\n"
                                  . "Segera transfer & balas dengan *bukti transfer*.";
                }

                $pesanCustomer = "*PESANAN DITERIMA ✅*\n\n"
                               . "Halo {$request->nama_customer},\n"
                               . "Pesanan *{$car->merk} {$car->model}* sudah kami terima!\n\n"
                               . "📅 Ambil  : " . Carbon::parse($request->tanggal_mulai)->format('d/m/Y') . " {$jam_mulai}\n"
                               . "📅 Kembali: " . Carbon::parse($request->tanggal_selesai)->format('d/m/Y') . " {$jam_selesai}\n"
                               . "💰 Total  : *Rp " . number_format($total_harga, 0, ',', '.') . "*\n\n"
                               . $infoRekening;

                Http::withHeaders(['Authorization' => env('WA_API_TOKEN')])
                    ->asForm()
                    ->post(env('WA_API_URL'), [
                        'target'      => $noHpCustomer,
                        'message'     => $pesanCustomer,
                        'countryCode' => '62',
                    ]);
                Log::info('WA Guest → Customer: ' . $noHpCustomer);
            }
        } catch (\Exception $e) {
            Log::error('WA Guest Customer Error: ' . $e->getMessage());
        }

        // --- WA KE MITRA ---
        try {
            $noHpMitra = $rental->no_telp_bisnis ?? null;
            if (!empty($noHpMitra)) {
                $pesanMitra = "🔔 *PESANAN BARU (VIA BOT)!*\n\n"
                            . "👤 Pemesan : {$request->nama_customer}\n"
                            . "📱 No HP   : {$request->telp_customer}\n"
                            . "🚗 Armada  : {$car->merk} {$car->model}\n"
                            . "📅 Ambil   : " . Carbon::parse($request->tanggal_mulai)->format('d/m/Y') . " {$jam_mulai}\n"
                            . "📅 Kembali : " . Carbon::parse($request->tanggal_selesai)->format('d/m/Y') . " {$jam_selesai}\n"
                            . "💰 Total   : Rp " . number_format($total_harga, 0, ',', '.') . "\n\n"
                            . "Konfirmasi di: " . env('APP_URL') . "/mitra/pesanan";

                Http::withHeaders(['Authorization' => env('WA_API_TOKEN')])
                    ->asForm()
                    ->post(env('WA_API_URL'), [
                        'target'      => $noHpMitra,
                        'message'     => $pesanMitra,
                        'countryCode' => '62',
                    ]);
                Log::info('WA Guest → Mitra: ' . $noHpMitra);
            }
        } catch (\Exception $e) {
            Log::error('WA Guest Mitra Error: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Pesanan diterima! Cek WhatsApp ' . $request->telp_customer . ' untuk konfirmasi & info pembayaran.');
    }
}
