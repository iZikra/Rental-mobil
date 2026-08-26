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
        $rentalId = null; // null berarti sedang di halaman utama (aggregator)

        if ($user) {
            if (isset($user->rental_id) && $user->rental_id) {
                $rentalId = $user->rental_id;
            } elseif (isset($user->branch_id) && $user->branch_id) {
                $branch = Branch::find($user->branch_id);
                $rentalId = $branch ? $branch->rental_id : null;
            }
        }

        if ($request && $request->has('rental_id') && $request->rental_id != 'global') {
            $rentalId = $request->rental_id ?: $rentalId;
        } elseif (session()->has('tenant_id')) {
            // Jika ada tenant_id di session (dari URL /?i=1)
            $rentalId = session('tenant_id');
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

    private function detectSeatsFromMessage(string $message): ?int
    {
        $msg = strtolower($message);
        if (preg_match('/\b(\d+)\s*(kursi|penumpang|seat)\b/u', $msg, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    private function detectFuelFromMessage(string $message): ?string
    {
        $msg = strtolower($message);
        if (preg_match('/\b(solar|pertalite|pertamax|bensin|listrik|ev)\b/u', $msg, $m)) {
            return $m[1]; // normalize
        }
        return null;
    }

    private function detectYearFromMessage(string $message): ?int
    {
        if (preg_match('/\b(20\d{2})\b/u', $message, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    private function buildOfflineReply(string $userName, string $userMessage, $mobils, array $availableCities): string
    {
        $msgNorm = strtolower($userMessage);
        $selectedCity = $this->detectCityFromMessage($userMessage, $availableCities);
        $selectedTransmission = $this->detectTransmissionFromMessage($userMessage);
        $selectedSeats = $this->detectSeatsFromMessage($userMessage);
        $selectedFuel = $this->detectFuelFromMessage($userMessage);
        $selectedYear = $this->detectYearFromMessage($userMessage);

        $lines = [];
        $greeting = ($userName === 'Pelanggan') ? "Baik" : "Siap Kak {$userName}";
        $lines[] = "{$greeting}. Saya bantu cek stok mobil yang tersedia ya.";

        if (!$availableCities) {
            $lines[] = "Saat ini data cabang/kota belum terbaca. Silakan coba lagi.";
            return implode("<br>", $lines);
        }

        $hasCarKeywords = preg_match('/\b(mobil|sewa|rental|booking|cari)\b/u', $msgNorm) === 1;
        $wantsList = preg_match('/\b(daftar|list|semua|tampilkan|sebutkan)\b/u', $msgNorm) === 1;

        if ($selectedCity || $selectedTransmission || $selectedSeats || $selectedFuel || $selectedYear || $hasCarKeywords || $wantsList) {
            $filtered = collect($mobils);

            if ($selectedCity) {
                $filtered = $filtered->filter(fn ($m) => strtolower((string) ($m->branch?->kota ?? '')) === strtolower($selectedCity));
            }

            if ($selectedTransmission) {
                $filtered = $filtered->filter(fn ($m) => str_contains(strtolower((string) ($m->transmisi ?? '')), $selectedTransmission));
            }
            
            if ($selectedSeats) {
                $filtered = $filtered->filter(fn ($m) => (int) ($m->jumlah_kursi ?? 0) >= $selectedSeats);
            }
            
            if ($selectedFuel) {
                // Sederhanakan pencocokan bensin
                $filtered = $filtered->filter(function ($m) use ($selectedFuel) {
                    $bbm = strtolower((string) ($m->bahan_bakar ?? ''));
                    if ($selectedFuel === 'bensin' && in_array($bbm, ['pertalite', 'pertamax', 'bensin'])) return true;
                    return str_contains($bbm, $selectedFuel);
                });
            }

            if ($selectedYear) {
                $filtered = $filtered->filter(fn ($m) => (int) ($m->tahun ?? 0) >= $selectedYear);
            }

            $filtered = $filtered->sortBy(fn ($m) => (float) ($m->harga_sewa ?? 0))->values();

            if ($filtered->isEmpty()) {
                $lines[] = "Belum ada unit yang cocok" . ($selectedCity ? " di {$selectedCity}" : "") . ($selectedTransmission ? " (transmisi {$selectedTransmission})" : "") . ($selectedSeats ? " (minimal {$selectedSeats} kursi)" : "") . ($selectedFuel ? " (bahan bakar {$selectedFuel})" : "") . ($selectedYear ? " (tahun >= {$selectedYear})" : "") . ".";
                $lines[] = "Kota yang tersedia: " . implode(', ', $availableCities) . ".";
                $lines[] = "Contoh: ketik \"mobil matic Pekanbaru\".";
                return implode("<br>", $lines);
            }

            $titleParts = [];
            if ($selectedTransmission) $titleParts[] = $selectedTransmission;
            if ($selectedCity) $titleParts[] = $selectedCity;
            $title = $titleParts ? implode(' di ', $titleParts) : 'yang tersedia';

            $greeting = ($userName === 'Pelanggan') ? "Berikut" : "Siap Kak {$userName}, ini";
            $lines[] = "{$greeting} daftar mobil {$title}:";
            $tglHariIni = date('Y-m-d');
            foreach ($filtered->take(12) as $i => $m) {
                $nama = trim(($m->merk ?? '') . ' ' . ($m->model ?? ''));
                $harga = number_format((float) ($m->harga_sewa ?? 0), 0, ',', '.');
                $mitra = $m->rental ? $m->rental->nama_rental : 'Pusat';
                $lines[] = ($i + 1) . ". {$nama} Rp {$harga}/hari (Mitra: {$mitra}) [LINK_BOOKING:{$m->id}|{$tglHariIni}]";
            }

            if ($filtered->count() > 12) {
                $lines[] = "Masih ada " . ($filtered->count() - 12) . " unit lagi. Tambahkan kriteria (misal: kursi 7 / SUV) biar saya saring.";
            }
            return implode("<br>", $lines);
        }

        $lines[] = "Kota yang tersedia: " . implode(', ', $availableCities) . ".";
        $lines[] = "Ketik format: \"mobil matic <kota>\" atau \"mobil manual <kota>\".";
        return implode("\n", $lines);
    }

    private function parseBookingLinks(string $reply, $user): string
    {
        if (preg_match_all('/\[LINK_BOOKING:(\d+)\|([^\]]+)\]/', $reply, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $fullTag = $match[0];
                $carId = $match[1];
                $tanggalRaw = $match[2];
                $car = Mobil::find($carId);
                
                if ($car) {
                    try {
                        $tgl_ambil = \Carbon\Carbon::parse($tanggalRaw)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $tgl_ambil = date('Y-m-d');
                    }
                    
                    $token = \Illuminate\Support\Str::uuid()->toString();
                    
                    $transaksiId = Transaksi::create([
                        'user_id' => $user ? $user->id : null,
                        'nama' => $user ? $user->name : 'Guest from Chatbot',
                        'no_hp' => '-',
                        'mobil_id' => $car->id,
                        'rental_id' => $car->rental_id,
                        'branch_id' => $car->branch_id,
                        'booking_token' => $token,
                        'token_expires_at' => now()->addMinutes(30),
                        'status' => 'Draft',
                        'total_harga' => $car->harga_sewa,
                        'tgl_ambil' => $tgl_ambil,
                        'jam_ambil' => '09:00',
                        'tgl_kembali' => $tgl_ambil,
                        'jam_kembali' => '09:00',
                        'catatan' => 'Temporary draft from Chatbot. Tanggal request: ' . $tanggalRaw,
                    ]);
                    
                    $uniqueLink = url('/rental/' . $car->rental_id . '/guest-booking/' . $token);
                    $htmlLink = '<a href="' . $uniqueLink . '" class="text-blue-600 font-bold underline hover:text-blue-800" target="_blank">Klik Disini untuk Booking</a>';
                    $reply = str_replace($fullTag, $htmlLink, $reply);
                } else {
                    $reply = str_replace($fullTag, '', $reply);
                }
            }
        }
        return $reply;
    }

    private function buildRecommendedCarLinks(array $carIds, $user): string
    {
        if (empty($carIds)) return '';
        
        $html = '<br><br><div class="mt-2 space-y-2">';
        $tgl_ambil = date('Y-m-d');
        
        $index = 1;
        foreach ($carIds as $carId) {
            $car = Mobil::with(['rental', 'branch'])->find($carId);
            if (!$car) continue;

            $token = \Illuminate\Support\Str::uuid()->toString();
            
            Transaksi::create([
                'user_id' => $user ? $user->id : null,
                'nama' => $user ? $user->name : 'Guest from Chatbot',
                'no_hp' => '-',
                'mobil_id' => $car->id,
                'rental_id' => $car->rental_id,
                'branch_id' => $car->branch_id,
                'booking_token' => $token,
                'token_expires_at' => now()->addMinutes(30),
                'status' => 'Draft',
                'total_harga' => $car->harga_sewa,
                'tgl_ambil' => $tgl_ambil,
                'jam_ambil' => '09:00',
                'tgl_kembali' => $tgl_ambil,
                'jam_kembali' => '09:00',
                'catatan' => 'Temporary draft from Chatbot Recommendation.',
            ]);
            
            $uniqueLink = url('/rental/' . $car->rental_id . '/guest-booking/' . $token);
            $namaMobil = trim(($car->merk ?? '') . ' ' . ($car->model ?? ''));
            $harga = number_format((float) ($car->harga_sewa ?? 0), 0, ',', '.');
            $mitra = $car->rental ? $car->rental->nama_rental : 'Pusat';
            $kota = $car->branch ? $car->branch->kota : '-';
            
            $html .= '<div class="p-3 border rounded-lg bg-gray-50 flex gap-3">';
            $html .= '<div class="font-bold text-gray-400">' . $index . '.</div>';
            $html .= '<div class="flex-1">';
            $html .= '<div class="font-bold text-gray-800">' . htmlspecialchars($namaMobil) . '</div>';
            $html .= '<div class="text-sm text-gray-600">Rp ' . $harga . '/hari | ' . htmlspecialchars($mitra) . ' (' . htmlspecialchars($kota) . ')</div>';
            $html .= '<div class="mt-2"><a href="' . $uniqueLink . '" class="inline-block bg-indigo-600 text-white text-xs font-bold py-1.5 px-3 rounded hover:bg-indigo-700" target="_blank">Booking Sekarang</a></div>';
            $html .= '</div></div>';
            
            $index++;
        }
        
        $html .= '</div>';
        return $html;
    }

    public function sendMessage(Request $request)
    {
        $mobils = collect(); // Inisialisasi awal untuk mencegah Undefined variable di blok catch

        try {
            $userMessage = $request->input('message');
            $user = auth()->user();
            $userName = $user ? $user->name : 'Pelanggan';
            $userLocation = null;

            // Deteksi lokasi user dari Profil atau Transaksi terakhir
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
            $rentalId = $this->resolveRentalId($request);

            // --- 2. MANAGEMENT HISTORY (Persistent from DB) & KONTEKS LOKASI ---
            $history = [];
            $chatLogsQuery = \App\Models\ChatLog::query();
            
            if ($user) {
                $chatLogsQuery->where('user_id', $user->id);
            } else {
                $chatLogsQuery->where('session_id', session()->getId());
            }

            $dbLogs = $chatLogsQuery->latest()
                ->take(6)
                ->get()
                ->reverse();

            foreach ($dbLogs as $log) {
                $history[] = [
                    'user' => $log->message,
                    'bot' => strip_tags($log->response) // Strip tags agar LLM tidak bingung melihat HTML
                ];
            }

            // Deteksi Kota dari Pesan Saat Ini ATAU History
            $availableCities = \App\Models\Branch::select('kota')->distinct()->pluck('kota')->toArray();
            $detectedCity = $this->detectCityFromMessage($userMessage, $availableCities);
            
            if (!$detectedCity) {
                // Cari di history mundur (dari yang paling baru)
                $reversedLogs = $dbLogs->reverse();
                foreach ($reversedLogs as $log) {
                    if ($log->message) {
                        $c = $this->detectCityFromMessage($log->message, $availableCities);
                        if ($c) {
                            $detectedCity = $c;
                            break;
                        }
                    }
                }
            }

            // --- 3. RETRIEVAL DATA MOBIL (Satu Platform) ---
            $mobilsQuery = Mobil::with(['branch', 'rental'])
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->whereHas('rental', fn ($q) => $q->where('status', 'active'));

            if ($rentalId !== null && $rentalId !== 'global') {
                $mobilsQuery->where('rental_id', $rentalId);
            }

            // Jika kota terdeteksi dari percakapan, filter stok hanya untuk kota tersebut
            if ($detectedCity) {
                $mobilsQuery->whereHas('branch', function ($q) use ($detectedCity) {
                    $q->where('kota', 'like', "%{$detectedCity}%");
                });
                $userLocation = $detectedCity; // Update userLocation agar dikirim ke AI
            }

            $mobils = $mobilsQuery->get();

            // --- 3. BANGUN CONTEXT ---
            $contextData = "DATA STOK MOBIL DAN LOKASI MITRA SAAT INI (REAL-TIME):\n";
            if ($mobils->isEmpty()) {
                $contextData .= "Maaf, saat ini tidak ada unit yang tersedia.\n";
            } else {
                foreach ($mobils as $m) {
                    $kota = $m->branch ? $m->branch->kota : 'Lokasi tidak diketahui';
                    $alamat = $m->branch ? $m->branch->alamat_lengkap : ($m->rental->alamat ?? 'Alamat tidak tersedia');
                    $hargaFormatted = number_format($m->harga_sewa, 0, ',', '.');
                    $rentalName = $m->rental ? $m->rental->nama_rental : '-';
                    
                    $contextData .= "- ID: {$m->id} | UNIT: {$m->merk} {$m->model} | Mitra: {$rentalName} | Cabang: {$kota} | Alamat Lengkap: {$alamat} | Harga: Rp {$hargaFormatted}/hari | Transmisi: {$m->transmisi} | BBM: {$m->bahan_bakar} | Kapasitas: {$m->jumlah_kursi} orang\n";
                }
            }

            // History sudah diambil di atas (Langkah 2)

            // --- 5. CALL RAG ENGINE ---
            $ragBaseUrl = rtrim(env('RAG_ENGINE_URL', 'http://127.0.0.1:5000'), '/');
            $startTime = microtime(true);
            $response = Http::withoutVerifying()->timeout(120)->post($ragBaseUrl . '/chat', [
                'question'      => $userMessage,
                'user_name'     => $userName,
                'context'       => $contextData,
                'rental_id'     => $rentalId ? (string) $rentalId : 'global',
                'kota'          => $userLocation,
                'history'       => $history,
                'current_date'  => date('Y-m-d')
            ]);
            $latency = round((microtime(true) - $startTime) * 1000, 2);
            
            // Tampilkan ke terminal `php artisan serve`
            error_log("\n[LLM RAG METRICS] ⚡ Respons AI selesai dalam: {$latency} ms\n");

            $responseData = $response->json();
            $botReply = $responseData['answer'] ?? $responseData['reply'] ?? null;
            $sources = $responseData['sources'] ?? [];
            $recommended_ids = $responseData['recommended_car_ids'] ?? [];

            if ($response->successful() && $botReply) {
                // Intercept LINK_BOOKING directives untuk balasan ke UI (hanya fallback jika LLM membangkang)
                $parsedReply = $this->parseBookingLinks($botReply, $user);

                // Tambahkan UI Card deterministik untuk mobil rekomendasi
                if (!empty($recommended_ids) && is_array($recommended_ids)) {
                    $parsedReply .= $this->buildRecommendedCarLinks($recommended_ids, $user);
                }

                // Simpan raw reply (dengan tag HTML yang ditambahkan) untuk history DB & session
                $rawBotReply = $parsedReply;

                // --- 5. SAVE LOG TO DATABASE ---
                try {
                    \App\Models\ChatLog::create([
                        'user_id' => $user ? $user->id : null,
                        'session_id' => session()->getId(),
                        'message' => (string) $userMessage,
                        'response' => $rawBotReply,
                        'rental_id' => $rentalId,
                        'model_used' => 'Llama-3-RAG-Hybrid',
                        'latency' => $latency,
                        'context_sources' => json_encode($sources)
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to save chat log: " . $e->getMessage());
                }

                $history[] = ['user' => $userMessage, 'bot' => strip_tags($rawBotReply)];
                session()->put('chatbot_history', array_slice($history, -10));
                
                return response()->json([
                    'reply' => $parsedReply,
                    'latency' => $latency
                ]);
            }

            Log::warning("Chatbot AI Bad Response: HTTP {$response->status()} " . json_encode($responseData));
            return response()->json([
                'reply' => "Mohon maaf, sistem sedang mengalami kendala teknis saat memproses permintaan Anda. Silakan coba lagi."
            ]);

        } catch (\Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            
            // FALLBACK: Gunakan offline reply jika AI mati
            $availableCities = Branch::distinct()->pluck('kota')->filter()->toArray();
            $fallbackReply = $this->buildOfflineReply($userName, $userMessage, $mobils, $availableCities);
            $fallbackReply = $this->parseBookingLinks($fallbackReply, $user);
            
            return response()->json([
                'reply' => $fallbackReply . "<br><br><i>(Catatan: Bot sedang dalam mode offline/fallback)</i>"
            ]);
        }
    }

    public function clearHistory()
    {
        $user = auth()->user();
        $rentalId = $this->resolveRentalId();
        
        $query = \App\Models\ChatLog::query();
        
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', session()->getId());
        }
        
        if ($rentalId && $rentalId !== 'global') {
            $query->where('rental_id', $rentalId);
        } else {
            $query->where(function($q) {
                $q->whereNull('rental_id')
                  ->orWhere('rental_id', 'global');
            });
        }
        
        $query->delete();
        
        session()->forget('chatbot_history');
        return response()->json(['status' => 'success', 'message' => 'History cleared from database and session']);
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

    public function smartSearch(Request $request)
    {
        try {
            $query = $request->query_input;
            $rentalId = $this->resolveRentalId($request);
            $bookedIds = $this->getBookedCarIds();

            $mobilsQuery = Mobil::with(['branch', 'rental'])
                ->where('status', 'tersedia')
                ->whereNotIn('id', $bookedIds)
                ->whereHas('rental', fn($q) => $q->where('status', 'active'));

            if ($rentalId !== null && $rentalId !== 'global') {
                $mobilsQuery->where('rental_id', $rentalId);
            }

            // FILTER KOTA (Jika user memilih lokasi di frontend)
            $selectedCity = $request->selected_city ?? $request->kota;
            if ($selectedCity) {
                $mobilsQuery->whereHas('branch', function($q) use ($selectedCity) {
                    $q->where('kota', 'like', "%{$selectedCity}%");
                });
            }
            
            // FILTER TAMBAHAN (Sesuai parameter dari frontend)
            if ($request->filled('jumlah_kursi')) {
                $mobilsQuery->where('jumlah_kursi', '>=', $request->jumlah_kursi);
            }
            
            if ($request->filled('bahan_bakar')) {
                // Konversi ke array kata kunci jika ada koma, atau gunakan like
                $bbm = strtolower($request->bahan_bakar);
                if ($bbm === 'bensin') {
                    $mobilsQuery->whereIn(DB::raw('LOWER(bahan_bakar)'), ['bensin', 'pertalite', 'pertamax']);
                } else {
                    $mobilsQuery->where(DB::raw('LOWER(bahan_bakar)'), 'like', "%{$bbm}%");
                }
            }
            
            if ($request->filled('tahun')) {
                $mobilsQuery->where('tahun', '>=', $request->tahun);
            }

            $mobils = $mobilsQuery->get();

            $stockContext = "DATA STOK MOBIL SAAT INI (REAL-TIME):\n";
            if ($mobils->isEmpty()) {
                $stockContext .= "STOK KOSONG.\n";
            } else {
                foreach ($mobils as $m) {
                    $kota = $m->branch->kota ?? 'Lokasi tidak diketahui';
                    $harga = number_format($m->harga_sewa);
                    $stockContext .= "- ID: {$m->id} | UNIT: {$m->merk} {$m->model} | KOTA: {$kota} | HARGA: Rp {$harga}/hari | TRANSMISI: {$m->transmisi} | BBM: {$m->bahan_bakar} | Kapasitas: {$m->jumlah_kursi} orang | TIPE: {$m->tipe_mobil}\n";
                }
            }

            $ragBaseUrl = rtrim(env('RAG_ENGINE_URL', 'http://127.0.0.1:5000'), '/');
            $response = Http::withoutVerifying()->timeout(120)->post($ragBaseUrl . '/search', [
                'query' => $query,
                'context' => $stockContext,
                'rental_id' => $rentalId ?: 'global',
                'kota' => $request->kota ?? null,
                'jumlah_kursi' => $request->jumlah_kursi,
                'bahan_bakar' => $request->bahan_bakar,
                'tahun' => $request->tahun
            ]);

            if ($response->failed()) {
                throw new \Exception("Flask RAG Engine returned error: " . $response->body());
            }

            $aiRes = $response->json();
            $searchResults = $aiRes['results'] ?? [];
            $summary = $aiRes['summary'] ?? 'Berikut adalah hasil pencarian berdasarkan kriteria Anda:';

            $result = [];
            foreach ($searchResults as $rec) {
                $mobil = Mobil::with(['branch', 'rental'])->find($rec['id']);
                if ($mobil) {
                    $token = \Illuminate\Support\Str::uuid()->toString();
                    
                    Transaksi::create([
                        'user_id' => null,
                        'nama' => 'Guest from Smart Search',
                        'no_hp' => '-',
                        'mobil_id' => $mobil->id,
                        'rental_id' => $mobil->rental_id,
                        'branch_id' => $mobil->branch_id,
                        'booking_token' => $token,
                        'token_expires_at' => now()->addMinutes(30),
                        'status' => 'Draft',
                        'total_harga' => $mobil->harga_sewa,
                        'tgl_ambil' => date('Y-m-d'),
                        'jam_ambil' => '09:00',
                        'tgl_kembali' => date('Y-m-d'),
                        'jam_kembali' => '09:00',
                        'catatan' => 'Temporary draft from Smart Search',
                    ]);
                    
                    $result[] = [
                        'id' => $mobil->id,
                        'nama' => "{$mobil->merk} {$mobil->model}",
                        'harga' => number_format($mobil->harga_sewa),
                        'gambar' => $mobil->image_url,
                        'booking_url' => url('/rental/' . $mobil->rental_id . '/guest-booking/' . $token),
                        'reason' => $rec['reason'] ?? '',
                        'scores' => $rec['scores'] ?? null,
                        'kota' => $mobil->branch->kota ?? 'Pusat',
                        'transmisi' => $mobil->transmisi,
                        'tipe' => $mobil->tipe_mobil,
                        'kursi' => $mobil->jumlah_kursi,
                        'mitra' => $mobil->rental->nama_rental ?? 'Pusat'
                    ];
                }
            }

            return response()->json([
                'status' => 'success',
                'summary' => $summary,
                'data' => $result,
                'source' => $aiRes['source'] ?? 'deterministic'
            ]);

        } catch (\Exception $e) {
            Log::error("Smart Search Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan pencarian cerdas.'
            ], 500);
        }
    }
}
