<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Rental;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    private function getBookedCarIds()
    {
        return Transaksi::whereIn('status', [
            'pending', 'menunggu_pembayaran', 'menunggu',
            'approved', 'disetujui', 'process',
            'disewa', 'sedang_jalan', 'sedang_disewa'
        ])->pluck('mobil_id')->toArray();
    }

    // ==========================================
    // 1. CEK KETERSEDIAAN (Tombol Menu)
    // ==========================================
    public function checkAvailability()
    {
        $bookedIds = $this->getBookedCarIds();

        // Ambil mobil yang TIDAK sibuk & status tersedia
        $mobil = Mobil::whereNotIn('id', $bookedIds)
            ->where('status', 'tersedia')
            ->get();

        if ($mobil->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => 'Mohon maaf Kak, saat ini semua unit kami sedang Full Booked (Disewa). 🙏'
            ]);
        }

        return response()->json([
            'status' => 'found',
            'data' => $mobil
        ]);
    }

    // ==========================================
    // 2. MENANGANI CHAT TEKS (AI & Keyword)
    // ==========================================
    public function sendMessage(Request $request)
    {
        $userMessage = $request->message;
        $lowerMessage = strtolower($userMessage);
        $bookedIds = $this->getBookedCarIds();

        // --- A. PENCARIAN CEPAT DATABASE (Keyword Match) ---
        // Gunakan 'merk' sesuai DatabaseSeeder
        $allMobils = Mobil::with('branch')->get(); 
        
        foreach ($allMobils as $m) {
            $brand = strtolower(trim($m->merk)); // Perbaikan typo 'merek' -> 'merk'
            $model = strtolower(trim($m->model));

            if (!empty($brand) && (str_contains($lowerMessage, $brand) || str_contains($lowerMessage, $model))) {
                $fullName = $m->merk . ' ' . $m->model;
                $harga = number_format($m->harga_sewa, 0, ',', '.');
                $isAvailable = ($m->status == 'tersedia') && !in_array($m->id, $bookedIds);

                if ($isAvailable) {
                    return response()->json([
                        'reply' => "<b>Ready!</b> Unit {$fullName} ({$m->branch->kota}) tersedia. ✅<br>Harga: Rp {$harga}/hari."
                    ]);
                }
            }
        }

        // --- B. AI CONTEXT (Menyediakan Data Lengkap untuk RAG) ---
        $availableMobils = Mobil::with(['branch', 'rental'])
            ->whereNotIn('id', $bookedIds)
            ->where('status', 'tersedia')
            ->get();

        $contextData = "DAFTAR MOBIL TERSEDIA DI PLATFORM:\n\n";

        $rentals = Rental::with(['mobils' => function($q) use ($bookedIds) {
            $q->whereNotIn('id', $bookedIds)->where('status', 'tersedia');
        }, 'mobils.branch'])->get();

        foreach ($rentals as $rental) {
            $contextData .= "--- RENTAL: {$rental->nama_rental} ---\n";
            foreach ($rental->mobils as $m) {
                $harga = number_format($m->harga_sewa, 0, ',', '.');
                $contextData .= "- {$m->merk} {$m->model} ({$m->tahun_buat}), Rp {$harga}. Lokasi: {$m->branch->kota}\n";
            }
            $contextData .= "\n";
        }

        try {
            // Kirim rental_id jika sedang berada di halaman spesifik (opsional)
            $rentalId = $request->rental_id ?? ''; 

            $response = Http::timeout(10)->post('http://127.0.0.1:5000/chat', [
                'question' => $userMessage,
                'context'   => $contextData,
                'rental_id' => $rentalId,
                'user_name' => Auth::check() ? Auth::user()->name : 'Sobat Rental'
            ]);

            if ($response->successful()) {
                return response()->json(['reply' => $response->json()['answer']]);
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error("Chatbot Error: " . $e->getMessage());
        }

        return response()->json(['reply' => "Maaf Kak, sistem cerdas kami sedang istirahat. Silakan tanya kembali atau cek tombol stok di bawah! 👇"]);
    }
}
