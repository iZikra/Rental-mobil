<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental; // Pastikan Model Rental diimport
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function sendMessage(Request $request)
    {
        try {
            $userMessage = $request->message;
            $user = auth()->user();
            $userName = $user ? $user->name : 'Pelanggan';
            $rentalId = $request->rental_id ?? 1;

            // 1. Ambil Data Rental untuk Context (Tapi tidak untuk disebut namanya)
            $rental = Rental::find($rentalId);
            
            // 2. Retrieval Data Mobil Ready
            $bookedIds = $this->getBookedCarIds();
            $mobils = Mobil::with(['branch'])
                ->where('rental_id', $rentalId)
                ->whereNotIn('id', $bookedIds)
                ->where('status', 'tersedia')
                ->get();

            // 3. Bangun Context (Netral & Anonim)
            $contextData = "DATA STOK MOBIL SAAT INI:\n";
            if ($mobils->isEmpty()) {
                $contextData .= "Tidak ada unit tersedia saat ini.\n";
            } else {
                foreach ($mobils as $m) {
                    $harga = number_format($m->harga_sewa, 0, ',', '.');
                    $contextData .= "- {$m->merk} {$m->model} | Harga: Rp {$harga}/hari\n";
                }
            }

            // 4. Management History
            $history = session()->get('chatbot_history', []);

            // 5. Kirim ke Python Flask
            $response = Http::timeout(30)->post('http://127.0.0.1:5000/chat', [
                'question'  => $userMessage,
                'user_name' => $userName,      // Mengirim 'Abil'
                'context'   => $contextData,   // Data stok real-time
                'rental_id' => (string)$rentalId,
                'history'   => $history
            ]);

            if ($response->successful()) {
                $botReply = $response->json()['answer'];

                // Simpan ke History
                $history[] = ['user' => $userMessage, 'bot' => $botReply];
                session()->put('chatbot_history', array_slice($history, -10));

                return response()->json(['reply' => $botReply]);
            }

            return response()->json(['reply' => "Maaf Zikrallah, server AI memberikan respon tidak valid."]);

        } catch (\Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            return response()->json(['reply' => "Maaf Kak, koneksi ke mesin AI terputus. Pastikan Flask sudah jalan!"]);
        }
    }
}