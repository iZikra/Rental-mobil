<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Helper: Ambil ID mobil yang sedang sibuk (Booked)
     */
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

        // --- A. PENCARIAN CEPAT DATABASE ---
        $allMobils = Mobil::all();
        
        foreach ($allMobils as $m) {
            $brand = strtolower(trim($m->merek));
            $model = strtolower(trim($m->model));

            // Cek kecocokan nama mobil
            if (!empty($brand) && (str_contains($lowerMessage, $brand) || str_contains($lowerMessage, $model))) {
                
                $fullName = $m->merek . ' ' . $m->model;
                $harga = number_format($m->harga_sewa, 0, ',', '.');
                $safeModel = addslashes($m->model); 

                // Cek Status Ketersediaan
                $isAvailable = ($m->status == 'tersedia') && !in_array($m->id, $bookedIds);

                if ($isAvailable) {
                    return response()->json([
                        'reply' => "
                            <b>Ready!</b> Unit {$fullName} tersedia. ✅<br>
                            Harga: Rp {$harga} / hari.<br>
                            #SHOW_CARS
                            <div class='mt-2'>
                                <button onclick=\"window.openWhatsApp('{$safeModel}')\" 
                                        class='bg-green-500 hover:bg-green-600 text-white w-full py-2 rounded text-xs font-bold transition flex items-center justify-center gap-1 shadow-sm'>
                                    <svg class='w-4 h-4' fill='currentColor' viewBox='0 0 24 24'><path d='M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z'/></svg>
                                    Booking via WhatsApp
                                </button>
                            </div>
                        "
                    ]);
                } else {
                    return response()->json([
                        'reply' => "<b>Yah, Kosong.</b><br>Unit {$fullName} sedang jalan hari ini. 🙏<br>Silakan cek unit lain."
                    ]);
                }
            }
        }

        // --- B. AI CONTEXT (Jika tidak ditemukan di DB) ---
        $availableMobils = Mobil::whereNotIn('id', $bookedIds)->where('status', 'tersedia')->get();
        $contextData = "DATA STOCK LIVE:\n";
        
        foreach ($availableMobils as $m) {
            $harga = number_format($m->harga_sewa, 0, ',', '.');
            $contextData .= "- {$m->merek} {$m->model}, Rp {$harga}.\n";
        }
        $contextData .= "\nATURAN: Syarat KTP & SIM. Lokasi Medan.";

        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:5000/chat', [
                'question' => $userMessage,
                'context'  => $contextData  
            ]);

            if ($response->successful()) {
                return response()->json(['reply' => $response->json()['answer']]);
            }
        } catch (\Exception $e) {
            // Fallback silent
        }

        return response()->json(['reply' => "Maaf, sistem sedang sibuk. Tapi Kakak bisa langsung cek stok mobil lewat tombol di bawah. 👇"]);
    }
}