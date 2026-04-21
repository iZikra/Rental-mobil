<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private function getBookedCarIds()
    {
        // Mengambil ID mobil yang sedang dalam proses sewa aktif
        return Transaksi::query()
            ->whereIn(DB::raw('LOWER(status)'), [
                'pending',
                'menunggu',
                'menunggu_pembayaran',
                'process',
                'approved',
                'disetujui',
                'disewa',
                'sedang_jalan',
                'sedang_disewa',
            ])
            ->where(function ($query) {
                // Jangan hitung jika ini adalah draft chatbot yang sudah expired
                $query->whereNull('token_expires_at')
                      ->orWhere('token_expires_at', '>', now());
            })
            ->pluck('mobil_id')
            ->toArray();
    }

    private function resolveRentalId(Request $request = null)
    {
        $user = auth()->user();
        $rentalId = 1;

        if ($user) {
            if (isset($user->rental_id) && $user->rental_id) {
                $rentalId = $user->rental_id;
            } elseif (isset($user->branch_id) && $user->branch_id) {
                $branch = Branch::find($user->branch_id);
                $rentalId = $branch ? $branch->rental_id : 1;
            }
        }

        if ($request && $request->has('rental_id')) {
            $rentalId = $request->rental_id ?: $rentalId;
        }

        return $rentalId;
    }

    private function detectCityFromMessage(string $message, array $availableCities): ?string
    {
        $msg = strtolower($message);
        $bestCity = null;
        $bestLen = 0;
        foreach ($availableCities as $city) {
            $c = strtolower((string) $city);
            if ($c === '') {
                continue;
            }
            if (str_contains($msg, $c) && strlen($c) > $bestLen) {
                $bestCity = (string) $city;
                $bestLen = strlen($c);
            }
        }
        return $bestCity;
    }

    private function detectTransmissionFromMessage(string $message): ?string
    {
        $msg = strtolower($message);
        if (preg_match('/\b(matic|matik|automatic|otomatis|auto)\b/u', $msg)) {
            return 'matic';
        }
        if (preg_match('/\b(manual)\b/u', $msg)) {
            return 'manual';
        }
        return null;
    }

    private function buildOfflineReply(string $userName, string $userMessage, $mobils, array $availableCities): string
    {
        $msgNorm = strtolower($userMessage);
        $selectedCity = $this->detectCityFromMessage($userMessage, $availableCities);
        $selectedTransmission = $this->detectTransmissionFromMessage($userMessage);

        $lines = [];
        $lines[] = "Siap Kak {$userName}. Saya bantu cek stok mobil yang tersedia ya.";

        if (!$availableCities) {
            $lines[] = "Saat ini data cabang/kota belum terbaca. Silakan coba lagi.";
            return implode("\n", $lines);
        }

        $hasCarKeywords = preg_match('/\b(mobil|sewa|rental|booking|cari)\b/u', $msgNorm) === 1;
        $wantsList = preg_match('/\b(daftar|list|semua|tampilkan|sebutkan)\b/u', $msgNorm) === 1;

        if ($selectedCity || $selectedTransmission || $hasCarKeywords || $wantsList) {
            $filtered = collect($mobils);

            if ($selectedCity) {
                $filtered = $filtered->filter(fn ($m) => strtolower((string) ($m->branch?->kota ?? '')) === strtolower($selectedCity));
            }

            if ($selectedTransmission) {
                $filtered = $filtered->filter(fn ($m) => str_contains(strtolower((string) ($m->transmisi ?? '')), $selectedTransmission));
            }

            $filtered = $filtered->sortBy(fn ($m) => (float) ($m->harga_sewa ?? 0))->values();

            if ($filtered->isEmpty()) {
                $lines[] = "Belum ada unit yang cocok" . ($selectedCity ? " di {$selectedCity}" : "") . ($selectedTransmission ? " (transmisi {$selectedTransmission})" : "") . ".";
                $lines[] = "Kota yang tersedia: " . implode(', ', $availableCities) . ".";
                $lines[] = "Contoh: ketik \"mobil matic Pekanbaru\".";
                return implode("\n", $lines);
            }

            $titleParts = [];
            if ($selectedTransmission) $titleParts[] = $selectedTransmission;
            if ($selectedCity) $titleParts[] = $selectedCity;
            $title = $titleParts ? implode(' di ', [$titleParts[0], $titleParts[1] ?? '']) : 'yang tersedia';
            $title = trim(str_replace(' di ', ' di ', $title));

            $lines[] = "Siap Kak {$userName}. Ini daftar mobil {$title}:";
            foreach ($filtered->take(12) as $i => $m) {
                $nama = trim(($m->merk ?? '') . ' ' . ($m->model ?? ''));
                $harga = number_format((float) ($m->harga_sewa ?? 0), 0, ',', '.');
                $tipe = $m->tipe_mobil ?: '-';
                $kursi = $m->jumlah_kursi ?: '-';
                $rentalName = $m->rental?->nama_rental ?: '-';
                $lines[] = ($i + 1) . ") {$nama} — Rp {$harga}/hari — {$tipe} — {$kursi} kursi — Mitra: {$rentalName}";
            }

            if ($filtered->count() > 12) {
                $lines[] = "Masih ada " . ($filtered->count() - 12) . " unit lagi. Tambahkan kriteria (misal: kursi 7 / SUV) biar saya saring.";
            }
            return implode("\n", $lines);
        }

        $lines[] = "Kota yang tersedia: " . implode(', ', $availableCities) . ".";
        $lines[] = "Ketik format: \"mobil matic <kota>\" atau \"mobil manual <kota>\".";
        return implode("\n", $lines);
    }

    public function sendMessage(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $user = auth()->user();
            $userName = $user ? $user->name : 'Pelanggan';
            $userLocation = null;

            // Deteksi lokasi user dari Profil (jika mitra) atau Riwayat Transaksi terakhir (jika customer)
            if ($user) {
                if ($user->role === 'mitra' && $user->branch_id) {
                    $branch = Branch::find($user->branch_id);
                    $userLocation = $branch ? $branch->kota : null;
                } else {
                    $lastTransaksi = Transaksi::where('user_id', $user->id)
                        ->with('branch')
                        ->latest()
                        ->first();
                    $userLocation = $lastTransaksi?->branch?->kota;
                }
            }

            $bookedIds = $this->getBookedCarIds();
            $availableCities = Branch::select('kota')->distinct()->pluck('kota')->filter()->values()->toArray();
            $selectedCity = $this->detectCityFromMessage((string) $userMessage, $availableCities);
            $selectedTransmission = $this->detectTransmissionFromMessage((string) $userMessage);

            $msgNorm = strtolower((string) $userMessage);
            $hasCarFilter = $selectedCity !== null || $selectedTransmission !== null;

            if ($hasCarFilter && $selectedCity && $selectedTransmission) {
                $cars = Mobil::with(['branch', 'rental'])
                    ->where('status', 'tersedia')
                    ->whereNotIn('id', $bookedIds)
                    ->whereHas('rental', fn ($q) => $q->where('status', 'active'))
                    ->whereHas('branch', fn ($q) => $q->where('kota', $selectedCity))
                    ->where('transmisi', 'LIKE', '%' . $selectedTransmission . '%')
                    ->orderBy('harga_sewa')
                    ->get();

                if ($cars->isEmpty()) {
                    return response()->json([
                        'reply' => "Maaf Kak {$userName}, untuk mobil {$selectedTransmission} di {$selectedCity} saat ini belum ada yang tersedia. Mau saya tampilkan opsi di kota lain atau mau manual?"
                    ]);
                }

                $lines = [];
                $lines[] = "Siap Kak {$userName}. Ini semua mobil {$selectedTransmission} yang tersedia di {$selectedCity}:";
                foreach ($cars as $idx => $m) {
                    $nama = trim(($m->merk ?? '') . ' ' . ($m->model ?? ''));
                    $harga = number_format((float) $m->harga_sewa, 0, ',', '.');
                    $tipe = $m->tipe_mobil ?: '-';
                    $kursi = $m->jumlah_kursi ?: '-';
                    $rentalName = $m->rental?->nama_rental ?: '-';
                    $lines[] = ($idx + 1) . ") {$nama} — Rp {$harga}/hari — {$tipe} — {$kursi} kursi — Mitra: {$rentalName}";
                }
                $lines[] = "";
                $lines[] = "Mau pilih nomor berapa? Nanti Kakak tinggal booking lewat menu Booking ya.";

                return response()->json(['reply' => implode("\n", $lines)]);
            }

            $rentalId = $this->resolveRentalId($request);

            // Pastikan Rental ada di DB (untuk kebutuhan RAG dokumen yang rental-specific)
            $rental = Rental::find($rentalId) ?: Rental::first();
            $rentalId = $rental ? $rental->id : 1;

            // --- 2. RETRIEVAL DATA MOBIL (Satu Platform: Ambil Semua Mobil Tersedia) ---
            $rentalNames = Rental::pluck('nama_rental')->unique()->filter()->values()->toArray();
            $rentalsStr = implode(', ', $rentalNames);

            $mobilsQuery = Mobil::with(['branch', 'rental'])
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->whereHas('rental', fn ($q) => $q->where('status', 'active'));

            if ($user && $user->role === 'mitra') {
                $mobilsQuery->where('rental_id', $rentalId);
            }

            $mobils = $mobilsQuery->get();

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
                    
                    // Metadata lengkap (Harga dikembalikan ke SQL berdasarkan skema Hybrid Retrieval untuk akurasi)
                    $hargaFormatted = number_format($m->harga_sewa, 0, ',', '.');
                    $rentalName = $m->rental ? ($m->rental->nama_rental ?? '-') : '-';
                    $contextData .= "- ID: {$m->id} | UNIT: {$m->merk} {$m->model} | Cabang: {$kota} | Harga: Rp {$hargaFormatted}/hari | Tipe: {$tipe} | Transmisi: {$m->transmisi} | Kursi: {$kursi} | BBM: {$bbm} | Mitra: {$rentalName}\n";
                }
            }

            // --- 4. MANAGEMENT HISTORY ---
            $history = session()->get('chatbot_history', []);

            try {
                $response = Http::timeout(15)->post('http://127.0.0.1:5000/chat', [
                    'question'      => $userMessage,
                    'user_name'     => $userName,
                    'user_location' => $userLocation, // Lokasi yang diketahui
                    'context'       => $contextData,
                    'rental_id'     => (string) $rentalId,
                    'history'       => $history
                ]);
            } catch (\Throwable $e) {
                Log::warning("Chatbot AI Connection Error: " . $e->getMessage());
                return response()->json([
                    'reply' => $this->buildOfflineReply($userName, (string) $userMessage, $mobils, $availableCities)
                ]);
            }

            $responseData = $response->json();
            $botReply = $responseData['answer'] ?? $responseData['reply'] ?? null;

            if ($response->successful() && $botReply) {
                // Intercept LINK_BOOKING directive for guest bookings
                if (preg_match('/\[LINK_BOOKING:(\d+)\|([^\]]+)\]/', $botReply, $matches)) {
                    $carId = $matches[1];
                    $tanggalRaw = $matches[2];
                    $car = Mobil::find($carId);
                    
                    if ($car) {
                        try {
                            $tgl_ambil = \Carbon\Carbon::parse($tanggalRaw)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $tgl_ambil = date('Y-m-d');
                        }
                        
                        $token = \Illuminate\Support\Str::uuid()->toString();
                        
                        Transaksi::create([
                            'user_id' => null,   // Membutuhkan user_id nullable di migrations
                            'mobil_id' => $car->id,
                            'rental_id' => $car->rental_id,
                            'branch_id' => $car->branch_id,
                            'booking_token' => $token,
                            'token_expires_at' => now()->addMinutes(15),
                            'status' => 'Pending',
                            'total_harga' => $car->harga_sewa,
                            'tgl_ambil' => $tgl_ambil,
                            'jam_ambil' => '09:00',
                            'tgl_kembali' => $tgl_ambil,
                            'jam_kembali' => '09:00',
                            'catatan' => 'Temporary draft from Chatbot. Tanggal request: ' . $tanggalRaw,
                        ]);
                        
                        $uniqueLink = url('/guest-booking/' . $token);
                        $htmlLink = '<a href="' . $uniqueLink . '" class="text-blue-600 font-bold underline hover:text-blue-800 break-all border-b border-blue-600" target="_blank">Klik Disini untuk Booking</a>';
                        $botReply = preg_replace('/\[LINK_BOOKING:.*?\]/', $htmlLink, $botReply);
                    }
                }

                $history[] = ['user' => $userMessage, 'bot' => $botReply];
                session()->put('chatbot_history', array_slice($history, -10));
                return response()->json(['reply' => $botReply]);
            }

            Log::warning("Chatbot AI Bad Response: HTTP {$response->status()} " . json_encode($responseData));
            return response()->json([
                'reply' => $this->buildOfflineReply($userName, (string) $userMessage, $mobils, $availableCities)
            ]);

        } catch (\Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            return response()->json([
                'reply' => "Koneksi bot sedang gangguan. Coba jalankan server AI:\n\npython python_service\\rag_engine.py\n\nLalu refresh halaman dan coba chat lagi."
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
            $rentalId = $this->resolveRentalId();

            $bookedIds = $this->getBookedCarIds();
            $mobils = Mobil::with(['branch'])
                ->where('status', 'tersedia')
                ->where('rental_id', $rentalId)
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
