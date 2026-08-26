<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

// --- MODELS ---
use App\Models\{Mobil, TentangKami, Transaksi, Branch, User, Rental};

// --- CONTROLLERS ---
use App\Http\Controllers\{
    ProfileController, PageController, MobilController, TransaksiController,
    AdminTransaksiController, AdminTentangKamiController, ChatbotController,
    KatalogController, MitraController, AdminBranchController, AdminRentalController,
    PaymentController, GuestBookingController, RagDocumentController, AdminChatlogController,
    RefundController
};
use App\Http\Middleware\IsMitra;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// BERANDA PUBLIK (Katalog Mobil)
Route::get('/storage-link', function () {
    try {
        $target = storage_path('app/public');
        $shortcut = public_path('storage');
        if (!file_exists($shortcut)) {
            symlink($target, $shortcut);
            return "Storage link created successfully.";
        }
        return "Storage link already exists.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage() . ". Your hosting might disable symlink(). You can manually move files from storage/app/public to public/storage.";
    }
});

Route::get('/clear-cache', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return "Cache berhasil dibersihkan! Silakan refresh halaman utama.";
});

// DEBUG: Test koneksi ke Fonnte WA API (hapus setelah testing)
Route::get('/test-wa', function () {
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->withHeaders(['Authorization' => env('WA_API_TOKEN')])
            ->asForm()
            ->post(env('WA_API_URL'), [
                'target'      => env('WA_TEST_TARGET', '6281234567890'),
                'message'     => 'Test dari InfinityFree: ' . now(),
                'countryCode' => '62',
            ]);
        return response()->json([
            'status'   => $response->status(),
            'body'     => $response->json(),
            'wa_url'   => env('WA_API_URL'),
            'wa_token' => substr(env('WA_API_TOKEN', ''), 0, 8) . '...',
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Proxy image server untuk hosting gratisan yang memblokir akses ke /storage
Route::get('/img-proxy/{path}', function ($path) {
    $path = str_replace(['img-proxy//', 'img-proxy/'], ['', ''], $path);
    
    // Semua kemungkinan lokasi fisik file di hosting (terutama InfinityFree)
    $possiblePaths = [
        storage_path('app/public/' . $path),
        public_path('storage/' . $path),
        base_path('storage/app/public/' . $path),
        base_path('public/storage/' . $path),
        __DIR__ . '/../storage/app/public/' . $path,
        __DIR__ . '/../public/storage/' . $path,
    ];

    foreach ($possiblePaths as $p) {
        if (file_exists($p)) {
            $mimeType = mime_content_type($p) ?: 'image/jpeg';
            return response()->file($p, ['Content-Type' => $mimeType]);
        }
    }

    // Fallback: Jika path di database tidak menyertakan folder (legacy data)
    $folders = ['identitas', 'sim', 'mobil_images', 'bukti_bayar'];
    foreach ($folders as $folder) {
        if (strpos($path, $folder . '/') === false) {
            foreach ($possiblePaths as $base) {
                $checkPath = dirname($base) . '/' . $folder . '/' . basename($path);
                if (file_exists($checkPath)) {
                    return response()->file($checkPath);
                }
            }
        }
    }

    abort(404, "Dokumen tidak ditemukan di server: " . $path);
})->where('path', '.*');

// Storage proxy: menggantikan symlink public/storage → storage/app/public
// Dibutuhkan di InfinityFree karena symlink tidak didukung
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

    return response()->file($filePath, [
        'Content-Type'        => $mimeType,
        'Content-Disposition' => 'inline',
    ]);
})->where('path', '.*')->middleware('auth');



Route::get('/', function(Request $request) {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('dashboard');
    } elseif (Auth::check() && Auth::user()->role === 'mitra') {
        return redirect()->route('mitra.dashboard');
    }



    $daftarKota = Branch::select('kota')->distinct()->pluck('kota');
    $daftarMitra = Rental::where('status', 'active')->get();
    
    // 1. Tentukan Tenant ID (Prioritaskan Request URL, baru Session)
    $tenantId = $request->query('tenant_id');
    
    // Jika ada tenant_id baru di URL, update session
    if ($tenantId) {
        session(['tenant_id' => $tenantId]);
    } else {
        // Jika tidak ada di URL, ambil dari session
        $tenantId = session('tenant_id');
    }

    // 2. Tentukan Mitra (dari dropdown filter)
    $mitraFilter = $request->query('mitra');
    
    // JIKA USER MEMILIH MITRA DI DROPDOWN, MAKA TENANT_ID SESSION HARUS KALAH/DIUPDATE
    if ($mitraFilter) {
        $tenantId = $mitraFilter;
        session(['tenant_id' => $mitraFilter]);
    }

    $query = Mobil::with(['rental', 'branch'])->where('status', 'tersedia')
                  ->whereHas('rental', fn($q) => $q->where('status', 'active'));

    // 3. Terapkan Filter Tunggal (Hanya satu rental_id agar tidak konflik)
    if ($tenantId) {
        $query->where('rental_id', $tenantId);
    }

    if ($request->filled('kota')) {
        $query->whereHas('branch', fn($q) => $q->where('kota', $request->kota));
    }

    return view('dashboard', [
        'mobils' => $query->paginate(12)->withQueryString(),
        'daftarKota' => $daftarKota,
        'daftarMitra' => $daftarMitra,
        'tenantId' => $tenantId // Kirim ke blade jika dibutuhkan untuk UI
    ]);
})->name('home');

Route::post('/midtrans/webhook', [PaymentController::class, 'webhook'])->name('midtrans.webhook');

// HALAMAN BOOKING (Publik, tetapi Submit butuh Login)
Route::prefix('order')->group(function () {
    Route::get('/form', [PageController::class, 'order'])->name('pages.order');
    Route::get('/form/create', [PageController::class, 'order'])->name('user.transaksi.create'); 
});

// KATALOG & HALAMAN INFORMASI (Publik)
// Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index'); // Dinonaktifkan sesuai permintaan user
// Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/tentang-kami', fn() => view('pages.tentang_kami', ['data' => TentangKami::all()]))->name('pages.about');

// ==========================================
// CHATBOT (PUBLIC)
// ==========================================
Route::prefix('bot')->name('chatbot.')->group(function () {
    Route::post('/send-message', [ChatbotController::class, 'sendMessage'])->name('send');
    Route::get('/check-cars', [ChatbotController::class, 'checkCars'])->name('check_cars');
    Route::post('/clear-history', [ChatbotController::class, 'clearHistory'])->name('clear_history');
    Route::post('/smart-search', [ChatbotController::class, 'smartSearch'])->name('smart_search');
});

// GUEST BOOKING ROUTES
Route::get('/rental/{rental_id}/guest-booking/{token}', [GuestBookingController::class, 'showForm'])->name('guest.booking.form');
Route::post('/rental/{rental_id}/guest-booking/{token}', [GuestBookingController::class, 'submitForm'])->name('guest.booking.submit');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/mitra/register', [\App\Http\Controllers\Auth\MitraRegisterController::class, 'showRegistrationForm'])->name('mitra.register');
    Route::post('/mitra/register', [\App\Http\Controllers\Auth\MitraRegisterController::class, 'register'])->name('mitra.register.submit');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // A. DASHBOARD ADMIN PUSAT
    // ==========================================
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin') { 
            return view('admin.dashboard', [
                'totalMobil'          => \App\Models\Mobil::count(),
                'totalMitra'          => Rental::count(),
                'totalCustomer'       => User::where('role', 'customer')->count(),
                'pendapatan'          => Transaksi::where('status', 'Selesai')->sum('total_harga'),
                'mitraMenungguVerif'  => Rental::where('status', 'inactive')->count(),
                'recentOrders'        => Transaksi::with(['user', 'mobil.rental'])->latest()->take(5)->get(),
            ]);
        }

        if ($user->role === 'mitra') {
            return redirect()->route('mitra.dashboard');
        }

        // Jika customer terlempar ke /dashboard, kembalikan ke beranda
        return redirect()->route('home');
    })->name('dashboard');

    // ==========================================
    // B. FITUR USER (TRANSAKSI)
    // ==========================================
    Route::prefix('order')->group(function () {
        Route::post('/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/riwayat', [TransaksiController::class, 'index'])->name('riwayat');
        Route::put('/{id}/batal', [TransaksiController::class, 'batalkanPesanan'])->name('transaksi.batal'); 
        Route::get('/{id}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
        Route::post('/refund', [RefundController::class, 'store'])->name('refund.store');
    });
Route::get('/check-status/{orderId}', [PaymentController::class, 'checkStatus']);
    Route::post('/midtrans/finish', [PaymentController::class, 'finish'])->name('midtrans.finish');

    // ==========================================
    // D. SUPER ADMIN EXCLUSIVE
    // ==========================================
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::resource('admin/tentang-kami', AdminTentangKamiController::class, ['names' => 'admin.tentang_kami']);
        Route::resource('admin/branches', AdminBranchController::class)->names('admin.branches');

        Route::prefix('admin')->name('admin.')->group(function() {
            // Admin hanya bisa AUDIT (lihat) transaksi — approve/reject/complete adalah tugas Mitra
            Route::controller(AdminTransaksiController::class)->group(function() {
                Route::get('/transaksi', 'index')->name('transaksi.index');
            });

            Route::controller(AdminRentalController::class)->group(function() {
                Route::get('/rentals', 'index')->name('rentals.index');
                Route::patch('/rentals/{id}/approve', 'approve')->name('rentals.approve');
                Route::patch('/rentals/{id}/block', 'block')->name('rentals.block');
                Route::delete('/rentals/{id}', 'destroy')->name('rentals.destroy');
            });

            Route::controller(RagDocumentController::class)->group(function() {
                Route::get('/rag-documents', 'indexAdmin')->name('rag.index');
                Route::post('/rag-documents', 'storeAdmin')->name('rag.store');
                Route::delete('/rag-documents/{filename}', 'destroyAdmin')->name('rag.destroy');
            });

            Route::get('/chat-logs', [AdminChatlogController::class, 'index'])->name('chat_logs.index');
        });
    });

    // ==========================================
    // E. MITRA / CABANG (LOGIKA ANTI-LOOP)
    // ==========================================
    Route::middleware([IsMitra::class])->prefix('mitra')->name('mitra.')->group(function () {
        Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');
        Route::post('/admin-assist', [MitraController::class, 'adminAssist'])->name('admin_assist');
        
        // Pengaturan Rental (DIPERBAIKI)
        Route::get('/pengaturan', [MitraController::class, 'pengaturan'])->name('pengaturan');
        Route::put('/pengaturan/update', [MitraController::class, 'updatePengaturan'])->name('pengaturan.update');
        
        // Cabang Management
        Route::get('/cabang', [MitraController::class, 'indexCabang'])->name('cabang.index');
        Route::post('/cabang', [MitraController::class, 'storeCabang'])->name('cabang.store');
        Route::put('/cabang/{id}', [MitraController::class, 'updateCabang'])->name('cabang.update');
        Route::delete('/cabang/{id}', [MitraController::class, 'destroyCabang'])->name('cabang.destroy');

        
        // Armada Management
        Route::get('/mobil', [MitraController::class, 'indexArmada'])->name('mobil.index');
        Route::get('/mobil/create', [MitraController::class, 'createArmada'])->name('mobil.create');
        Route::post('/mobil', [MitraController::class, 'storeArmada'])->name('mobil.store');
        Route::get('/mobil/{id}/edit', [MitraController::class, 'editArmada'])->name('mobil.edit');
        Route::put('/mobil/{id}', [MitraController::class, 'updateArmada'])->name('mobil.update');
        Route::delete('/mobil/{id}', [MitraController::class, 'destroyArmada'])->name('mobil.destroy');

        // Order Management
        Route::get('/pesanan', [MitraController::class, 'indexPesanan'])->name('pesanan.index');
        Route::post('/pesanan/{id}/konfirmasi', [MitraController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');
        Route::post('/pesanan/{id}/tolak', [MitraController::class, 'tolakPesanan'])->name('pesanan.tolak');
        Route::post('/pesanan/{id}/selesai', [MitraController::class, 'selesaikanPesanan'])->name('pesanan.selesai');

        // Refund Management
        Route::get('/refunds', [MitraController::class, 'indexRefunds'])->name('refunds.index');
        Route::post('/refunds/{id}/setujui', [RefundController::class, 'approve'])->name('refunds.approve');
        Route::post('/refunds/{id}/tolak', [RefundController::class, 'reject'])->name('refunds.reject');

        Route::controller(RagDocumentController::class)->group(function() {
            Route::get('/rag-documents', 'indexMitra')->name('rag.index');
            Route::post('/rag-documents', 'storeMitra')->name('rag.store');
            Route::delete('/rag-documents/{filename}', 'destroyMitra')->name('rag.destroy');
        });
    });

    // ==========================================
    // F. PROFILE
    // ==========================================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| G. UTILITIES
|--------------------------------------------------------------------------
*/
Route::get('/tampilkan-gambar/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    if (!file_exists($path)) abort(404);
    return Response::file($path);
})->name('storage.view');

require __DIR__.'/auth.php';
