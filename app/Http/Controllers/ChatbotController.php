<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private function getBookedCarIds()
    {
        // Mengambil ID mobil yang sedang dalam proses sewa aktif
        return Transaksi::whereIn('status', [
            'Pending', 'pending', 'MENUNGGU', 'menunggu',
            'pending', 'menunggu_pembayaran', 'menunggu',
            'approved', 'disetujui', 'process',
            'disewa', 'sedang_jalan', 'sedang_disewa'
        ])->pluck('mobil_id')->toArray();
    }

    public function sendMessage(Request $request)
    {
        try {
            $userMessage = $request->message;
            $user = auth()->user();
            $userName = $user ? $user->name : 'Pelanggan';

            // --- PERBAIKAN LOGIKA RENTAL ID (Mengingat tabel users tidak punya rental_id) ---
            $rentalId = 1; // Default
            
            if ($user) {
                if (isset($user->rental_id) && $user->rental_id) {
                    $rentalId = $user->rental_id;
                } elseif (isset($user->branch_id) && $user->branch_id) {
                    // Cari rental_id melalui tabel branches
                    $branch = Branch::find($user->branch_id);
                    $rentalId = $branch ? $branch->rental_id : 1;
                }
            }
            
            // Override jika ada rental_id di request (untuk testing)
            $rentalId = $request->rental_id ?? $rentalId;

            // Pastikan Rental ada di DB
            $rental = Rental::find($rentalId) ?: Rental::first();
            $rentalId = $rental->id;

            // --- 2. RETRIEVAL DATA MOBIL (Satu Platform: Ambil Semua Mobil Tersedia) ---
            $bookedIds = $this->getBookedCarIds();
            
            // Ambil semua nama rental yang terdaftar
            $rentalNames = Rental::pluck('nama_rental')->unique()->filter()->values()->toArray();
            $rentalsStr = implode(', ', $rentalNames);

            // Kita ambil semua mobil yang statusnya 'tersedia' di platform ini
            $mobils = Mobil::with(['branch'])
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->get();

            // --- 3. BANGUN CONTEXT (Data Real-time untuk AI) ---
            $availableCities = $mobils
                ->map(fn ($m) => $m->branch ? $m->branch->kota : null)
                ->filter()
                ->unique()
                ->values()
                ->toArray();
            $citiesStr = implode(', ', $availableCities);

            $contextData = "DATA RENTAL YANG TERDAFTAR:\n" . ($rentalsStr ?: "Tidak ada rental") . "\n\n";
            $contextData .= "DATA KOTA YANG TERSEDIA DI RENTAL INI:\n" . ($citiesStr ?: "Tidak ada cabang") . "\n\n";
            $contextData .= "DATA STOK MOBIL SAAT INI (REAL-TIME):\n";
            if ($mobils->isEmpty()) {
                $contextData .= "Maaf, saat ini tidak ada unit yang tersedia untuk disewa.\n";
            } else {
                foreach ($mobils as $m) {
                    $kota = $m->branch ? $m->branch->kota : 'Lokasi tidak diketahui';
                    $tipe = $m->tipe_mobil ?: '-';
                    $kursi = $m->jumlah_kursi ?: '-';
                    $bbm = $m->bahan_bakar ?: '-';
                    
                    // Metadata lengkap TANPA Harga (Harga dialihkan ke RAG ChromaDB)
                    $contextData .= "- UNIT: {$m->merk} {$m->model} | Cabang: {$kota} | Tipe: {$tipe} | Transmisi: {$m->transmisi} | Kursi: {$kursi} | BBM: {$bbm}\n";
                }
            }

            // --- 4. MANAGEMENT HISTORY ---
            $history = session()->get('chatbot_history', []);

            // --- 5. KIRIM KE PYTHON FLASK (RAG ENGINE) ---
            $response = Http::timeout(30)->post('http://localhost:5000/chat', [
                'question'  => $userMessage,
                'user_name' => $userName,
                'context'   => $contextData,   
                'rental_id' => (string)$rentalId,
                'history'   => $history
            ]);

            if ($response->successful()) {
                $botReply = $response->json()['answer'];

                // Simpan ke History (Limit 10 percakapan terakhir)
                $history[] = ['user' => $userMessage, 'bot' => $botReply];
                session()->put('chatbot_history', array_slice($history, -10));

                return response()->json(['reply' => $botReply]);
            }

            return response()->json(['reply' => "Maaf, server AI sedang sibuk. Silakan coba lagi nanti."]);

        } catch (\Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            return response()->json([
                'reply' => "Maaf, sistem sedang ada gangguan. Coba lagi sebentar ya."
            ]);
        }
    }

    public function clearHistory()
    {
        session()->forget('chatbot_history');
        return response()->json(['status' => 'success', 'message' => 'History cleared']);
    }

    public function checkCars()
    {
        try {
            $user = auth()->user();
            
            // Logika Rental ID sama dengan sendMessage
            $rentalId = 1;
            if ($user) {
                if (isset($user->rental_id) && $user->rental_id) {
                    $rentalId = $user->rental_id;
                } elseif (isset($user->branch_id) && $user->branch_id) {
                    $branch = Branch::find($user->branch_id);
                    $rentalId = $branch ? $branch->rental_id : 1;
                }
            }

            $bookedIds = $this->getBookedCarIds();
            $mobils = Mobil::with(['branch'])
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->get();

            if ($mobils->isEmpty()) {
                return response()->json([
                    'status' => 'empty',
                    'message' => 'Maaf, saat ini tidak ada unit yang tersedia.'
                ]);
            }

            return response()->json([
                'status' => 'found',
                'data' => $mobils
            ]);

        } catch (\Exception $e) {
            Log::error("Chatbot CheckCars Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data mobil.'
            ]);
        }
    }
}
