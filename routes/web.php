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
    KatalogController, MitraController, AdminBranchController, AdminRentalController
};
use App\Http\Middleware\IsMitra;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function() {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // A. DASHBOARD SELECTOR (LOGIKA ROLE)
    // ==========================================
    Route::get('/dashboard', function (Request $request) {
        $user = Auth::user();

        // 1. Jika Admin Pusat - Langsung VIEW (Jangan Redirect)
        if ($user->role === 'admin') { 
            return view('admin.dashboard', [
                'totalMobil'    => Mobil::count(),
                'totalMitra'    => Rental::count(),
                'totalUser'     => User::where('role', 'user')->count(),
                'pendapatan'    => Transaksi::where('status', 'Selesai')->sum('total_harga'),
                'transaksiBaru' => Transaksi::where('status', 'Pending')->count(),
                'recentOrders'  => Transaksi::with(['user', 'mobil.rental'])->latest()->take(5)->get()
            ]);
        }

        // 2. Jika Mitra Cabang - Gunakan Redirect satu kali saja
        if ($user->role === 'mitra') {
            return redirect()->route('mitra.dashboard');
        }

        // 3. Jika User Biasa / Customer - Langsung VIEW
        $daftarKota = Branch::select('kota')->distinct()->pluck('kota');
        $query = Mobil::with(['rental', 'branch'])->where('status', 'tersedia')
                      ->whereHas('rental', fn($q) => $q->where('status', 'active'));

        if ($request->filled('kota')) {
            $query->whereHas('branch', fn($q) => $q->where('kota', $request->kota));
        }

        return view('dashboard', ['mobils' => $query->get(), 'daftarKota' => $daftarKota]);
    })->name('dashboard');

    // ==========================================
    // B. FITUR USER (KATALOG & TRANSAKSI)
    // ==========================================
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');
    Route::get('/tentang-kami', fn() => view('pages.tentang_kami', ['data' => TentangKami::all()]))->name('pages.about');

    Route::prefix('order')->group(function () {
        Route::get('/form', [PageController::class, 'order'])->name('pages.order');
        Route::get('/form/create', [PageController::class, 'order'])->name('user.transaksi.create'); 
        Route::post('/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/riwayat', [TransaksiController::class, 'index'])->name('riwayat');
        Route::post('/{id}/upload', [TransaksiController::class, 'upload'])->name('transaksi.upload');
        Route::put('/{id}/batal', [TransaksiController::class, 'batalkanPesanan'])->name('transaksi.batal'); 
        Route::get('/{id}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
    });

    // ==========================================
    // C. CHATBOT GEMINI AI
    // ==========================================
    Route::prefix('bot')->name('chatbot.')->group(function () {
        Route::post('/send-message', [ChatbotController::class, 'sendMessage'])->name('send');
        Route::post('/auto-book', [ChatbotController::class, 'autoBook'])->name('book');
    });

    // ==========================================
    // D. SUPER ADMIN EXCLUSIVE
    // ==========================================
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::resource('admin/tentang-kami', AdminTentangKamiController::class, ['names' => 'admin.tentang_kami']);
        Route::resource('admin/branches', AdminBranchController::class)->names('admin.branches');

        Route::prefix('admin')->name('admin.')->group(function() {
            Route::controller(AdminTransaksiController::class)->group(function() {
                Route::get('/transaksi', 'index')->name('transaksi.index');
                Route::patch('/transaksi/{id}/approve', 'approve')->name('transaksi.approve');
                Route::patch('/transaksi/{id}/reject', 'reject')->name('transaksi.reject');
                Route::patch('/transaksi/{id}/complete', 'complete')->name('transaksi.complete');
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