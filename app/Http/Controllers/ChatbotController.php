<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    // 1. CEK KETERSEDIAAN ARMADA
    public function checkAvailability()
    {
        // Ambil 3 mobil yang statusnya Tersedia (random)
        $mobils = Mobil::where('status', 'Tersedia')->inRandomOrder()->take(3)->get();

        if ($mobils->count() > 0) {
            return response()->json([
                'status' => 'found',
                'message' => 'Berikut adalah armada yang siap jalan sekarang:',
                'data' => $mobils
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'message' => 'Maaf, semua armada sedang jalan saat ini. 😢'
        ]);
    }

    // 2. PROSES BOOKING OTOMATIS
    public function autoBook(Request $request)
    {
        // A. CEK SYARAT & KETENTUAN (Validasi User)
        $user = Auth::user();
        
        // Contoh Syarat: Profil harus lengkap (No HP & Alamat tidak boleh kosong)
        /* Jika kolom no_hp/alamat belum ada di tabel users, Anda bisa skip validasi ini 
           atau sesuaikan dengan kebutuhan */
        // if (empty($user->no_hp) || empty($user->alamat)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Gagal! Lengkapi profil (No HP & Alamat) Anda terlebih dahulu di menu Profil.'
        //     ]);
        // }

        // B. CEK APAKAH USER PUNYA TRANSAKSI PENDING? (Cegah Spam)
        $pending = Transaksi::where('user_id', $user->id)->where('status', 'Pending')->first();
        if ($pending) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda masih memiliki pesanan Pending. Harap selesaikan pembayaran dulu ya.'
            ]);
        }

        // C. PROSES BOOKING (Default: Sewa 1 Hari Mulai Besok)
        $mobil = Mobil::find($request->mobil_id);
        
        if(!$mobil || $mobil->status != 'Tersedia') {
            return response()->json([
                'status' => 'error',
                'message' => 'Yah, mobil ini baru saja disewa orang lain.'
            ]);
        }

        // Buat Transaksi Cepat
        $trx = Transaksi::create([
            'user_id' => $user->id,
            'mobil_id' => $mobil->id,
            'nama' => $user->name,
            'no_hp' => '-', // Nanti user update sendiri
            'alamat' => '-', // Nanti user update sendiri
            'tgl_ambil' => Carbon::tomorrow(),
            'jam_ambil' => '09:00',
            'tgl_kembali' => Carbon::tomorrow()->addDay(),
            'jam_kembali' => '09:00',
            'lama_sewa' => 1,
            'total_harga' => $mobil->harga, // Harga 1 hari
            'status' => 'Pending',
            'sopir' => 'lepas_kunci',
            'lokasi_ambil' => 'kantor',
            'lokasi_kembali' => 'kantor'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil! Mobil ' . $mobil->model . ' telah dibooking untuk besok.',
            'redirect_url' => route('riwayat')
        ]);
    }
    // 3. MENANGANI CHAT TEKS (HYBRID LOGIC)
    public function sendMessage(Request $request)
    {
        $message = strtolower($request->message); // Ubah ke huruf kecil biar mudah dicek

        // --- LOGIKA 1: SIMPLE RULE-BASED (Pemicu Fitur Baru) ---
        // Jika user bertanya soal stok/booking lewat teks, arahkan ke tombol fitur baru
        if (str_contains($message, 'mobil') || str_contains($message, 'sewa') || str_contains($message, 'booking')) {
            return response()->json([
                'reply' => 'Untuk mengecek ketersediaan mobil atau melakukan booking, silakan klik tombol <b>"🚗 Cek Mobil Ready"</b> di bawah ya! 👇'
            ]);
        }

        // --- LOGIKA 2: INTEGRASI LLM / RAG (Tempat Kodingan Lama Anda) ---
        /* Di sini Anda bisa memanggil Service Python / API LLM Anda yang lama.
           Contoh Pseudo-code:
           $response = Http::post('http://localhost:5000/chat', ['query' => $request->message]);
           $reply = $response->json()['answer'];
        */

        // SEMENTARA (Placeholder agar tidak eror)
        $reply = "Maaf, saat ini saya baru bisa membantu Cek Stok dan Booking Mobil secara otomatis. Fitur tanya-jawab AI sedang maintenance.";

        return response()->json([
            'reply' => $reply
        ]);
    }
}