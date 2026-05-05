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
    PaymentController, GuestBookingController
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

// Proxy image server untuk hosting gratisan yang memblokir akses ke /storage
// Proxy image server untuk hosting gratisan yang memblokir akses ke /storage
Route::get('/img-proxy/{path}', function ($path) {
    $path = str_replace(['img-proxy//', 'img-proxy/'], ['', ''], $path);
    
    // Semua kemungkinan lokasi fisik file di hosting InfinityFree
    $possiblePaths = [
        storage_path('app/public/' . $path),
        public_path('storage/' . $path),
        base_path('public/storage/' . $path),
        base_path('storage/' . $path),
        __DIR__ . '/../public/storage/' . $path,
        __DIR__ . '/../storage/app/public/' . $path
    ];

    foreach ($possiblePaths as $p) {
        if (file_exists($p)) {
            return response()->file($p);
        }
    }

    // Coba tambahkan/hilangkan mobil_images/
    if (strpos($path, 'mobil_images/') !== false) {
        $cleanPath = str_replace('mobil_images/', '', $path);
        $possiblePathsClean = [
            storage_path('app/public/mobil_images/' . $cleanPath),
            public_path('storage/mobil_images/' . $cleanPath),
            base_path('public/storage/mobil_images/' . $cleanPath),
            __DIR__ . '/../public/storage/mobil_images/' . $cleanPath
        ];
        foreach ($possiblePathsClean as $p) {
            if (file_exists($p)) {
                return response()->file($p);
            }
        }
    }

    abort(404, "File not found: " . $path);
})->where('path', '.*');

Route::get('/', function(Request $request) {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('dashboard');
    } elseif (Auth::check() && Auth::user()->role === 'mitra') {
        return redirect()->route('mitra.dashboard');
    }

    // Tangkap parameter 'tenant_id' (rental_id tenant) jika ada, dan simpan ke session
    if ($request->has('tenant_id')) {
        session(['tenant_id' => $request->query('tenant_id')]);
    }

    $daftarKota = Branch::select('kota')->distinct()->pluck('kota');
    $daftarMitra = Rental::where('status', 'active')->get();
    
    // Filter berdasarkan tenant jika ada 'tenant_id' di URL atau di Session
    $tenantId = $request->query('tenant_id') ?? session('tenant_id');
    
    $query = Mobil::with(['rental', 'branch'])->where('status', 'tersedia')
                  ->whereHas('rental', fn($q) => $q->where('status', 'active'));

    if ($tenantId) {
        $query->where('rental_id', $tenantId);
    }

    if ($request->filled('kota')) {
        $query->whereHas('branch', fn($q) => $q->where('kota', $request->kota));
    }

    if ($request->filled('mitra')) {
        $query->where('rental_id', $request->mitra);
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
Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');
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
Route::get('/guest-booking/{token}', [GuestBookingController::class, 'showForm'])->name('guest.booking.form');
Route::post('/guest-booking/{token}', [GuestBookingController::class, 'submitForm'])->name('guest.booking.submit');

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
