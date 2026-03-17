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

            // --- 2. RETRIEVAL DATA MOBIL (Filter per Rental agar data tidak bocor) ---
            $bookedIds = $this->getBookedCarIds();
            
            // Kita ambil mobil yang statusnya 'tersedia' DAN tidak ada di daftar booking aktif
            $mobils = Mobil::with(['branch'])
                ->where('rental_id', $rentalId)
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->get();

            // --- 3. BANGUN CONTEXT (Data Real-time untuk AI) ---
            $contextData = "DATA STOK MOBIL SAAT INI (REAL-TIME):\n";
            if ($mobils->isEmpty()) {
                $contextData .= "Maaf, saat ini tidak ada unit yang tersedia untuk disewa.\n";
            } else {
                foreach ($mobils as $m) {
                    $harga = number_format($m->harga_sewa, 0, ',', '.');
                    $kota = $m->branch ? $m->branch->kota : 'Lokasi tidak diketahui';
                    
                    // Metadata lengkap agar AI bisa memfilter (Harga, Transmisi, Kapasitas)
                    $contextData .= "- {$m->merk} {$m->model} | Cabang: {$kota} | Harga: Rp {$harga}/hari | Transmisi: {$m->transmisi} | Kapasitas: {$m->kapasitas} orang\n";
                }
            }

            // --- 4. MANAGEMENT HISTORY ---
            $history = session()->get('chatbot_history', []);

            // --- 5. KIRIM KE PYTHON FLASK (RAG ENGINE) ---
            $response = Http::timeout(30)->post('http://127.0.0.1:5000/chat', [
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
                'reply' => "Terjadi kesalahan sistem: " . $e->getMessage()
            ]);
        }
    }
}