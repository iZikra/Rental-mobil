<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Rental;
use App\Models\Branch; // WAJIB DITAMBAHKAN UNTUK FILTER KOTA
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Ditambahkan untuk logging yang rapi

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
    // 2. MENANGANI CHAT TEKS (Integrasi RAG & Metadata Filtering)
    // ==========================================
    public function sendMessage(Request $request)
    {
        $userMessage = $request->message;
        $lowerMessage = strtolower($userMessage);
        $bookedIds = $this->getBookedCarIds();

        // --- FASE 1: EKSTRAKSI METADATA (FILTER KOTA) ---
        // Deteksi apakah user menyebut nama kota di dalam chatnya
        $daftarKota = Branch::select('kota')->distinct()->pluck('kota')->map(fn($k) => strtolower($k))->toArray();
        $kotaTerdeteksi = null;
        
        foreach ($daftarKota as $kota) {
            if (str_contains($lowerMessage, $kota)) {
                $kotaTerdeteksi = $kota;
                break; // Berhenti mencari jika sudah ketemu kotanya
            }
        }

        // --- FASE 2: RETRIEVAL (Tarik Data dengan Filter Ketat) ---
        $query = Mobil::with(['branch', 'rental'])
            ->whereNotIn('id', $bookedIds)
            ->where('status', 'tersedia');

        // Jika kota terdeteksi, KUNCI query hanya untuk kota tersebut (Ini syarat mutlak Dosen!)
        if ($kotaTerdeteksi) {
            $query->whereHas('branch', function($q) use ($kotaTerdeteksi) {
                $q->where('kota', 'LIKE', "%{$kotaTerdeteksi}%");
            });
        }

        $filteredMobils = $query->get();

        // --- FASE 3A: PENCARIAN CEPAT (Fast Keyword Match) ---
        foreach ($filteredMobils as $m) {
            $brand = strtolower(trim($m->merk));
            $model = strtolower(trim($m->model));

            // Sekarang pencarian cepat ini sudah AMAN karena $filteredMobils sudah difilter berdasarkan kota
            if (!empty($brand) && (str_contains($lowerMessage, $brand) || str_contains($lowerMessage, $model))) {
                $fullName = $m->merk . ' ' . $m->model;
                $harga = number_format($m->harga_sewa, 0, ',', '.');
                return response()->json([
                    'reply' => "<b>Ready!</b> Unit {$fullName} dari mitra <b>{$m->rental->nama_rental}</b> ({$m->branch->kota}) tersedia. ✅<br>Harga: Rp {$harga}/hari."
                ]);
            }
        }

        // --- FASE 3B: AI CONTEXT AUGMENTATION (Untuk dikirim ke Flask) ---
        if ($filteredMobils->isEmpty()) {
            $namaKota = $kotaTerdeteksi ? strtoupper($kotaTerdeteksi) : "lokasi tersebut";
            $contextData = "INFO SISTEM: Saat ini TIDAK ADA mobil yang tersedia di {$namaKota}. Minta user mencari di kota lain.\n";
        } else {
            $headerKota = $kotaTerdeteksi ? " DI KOTA " . strtoupper($kotaTerdeteksi) : "";
            $contextData = "DAFTAR MOBIL TERSEDIA{$headerKota}:\n\n";
            
            foreach ($filteredMobils as $m) {
                $harga = number_format($m->harga_sewa, 0, ',', '.');
                $contextData .= "- Rental: {$m->rental->nama_rental} | Unit: {$m->merk} {$m->model} ({$m->tahun_buat}) | Lokasi: {$m->branch->kota} | Harga: Rp {$harga}/hari.\n";
            }
        }

        // --- FASE 4: KIRIM KE PYTHON FLASK (LLM API) ---
        try {
            $rentalId = $request->rental_id ?? ''; 

            $response = Http::timeout(15)->post('http://127.0.0.1:5000/chat', [
                'question'  => $userMessage,
                'context'   => $contextData, // Context sekarang sangat efisien dan terfilter!
                'rental_id' => $rentalId,
                'user_name' => Auth::check() ? Auth::user()->name : 'Sobat Rental'
            ]);

            if ($response->successful()) {
                return response()->json(['reply' => $response->json()['answer']]);
            } else {
                Log::error("Flask API membalas dengan status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Chatbot Error (Gagal menghubungi Flask): " . $e->getMessage());
        }

        return response()->json(['reply' => "Maaf Kak, sistem cerdas kami sedang mengalami kendala jaringan. Silakan cek ketersediaan melalui tombol stok di bawah! 👇"]);
    }
}