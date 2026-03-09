<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

// --- MODELS ---
use App\Models\Mobil;
use App\Models\TentangKami;
use App\Models\Transaksi;
use App\Models\Branch;
use App\Models\User;
use App\Models\Rental;

// --- CONTROLLERS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\TransaksiController;      
use App\Http\Controllers\AdminTransaksiController; 
use App\Http\Controllers\AdminTentangKamiController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\AdminBranchController;
use App\Http\Controllers\AdminRentalController;
use App\Http\Middleware\IsMitra;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
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

        // 1. Dashboard Admin
        if ($user->role === 'admin') { 
            $data = [
                'totalMobil'    => Mobil::count(),
                'totalMitra'    => Rental::count(),
                'totalUser'     => User::where('role', 'user')->count(),
                'pendapatan'    => Transaksi::where('status', 'Selesai')->sum('total_harga'),
                'transaksiBaru' => Transaksi::where('status', 'Pending')->count(),
                'recentOrders'  => Transaksi::with(['user', 'mobil.rental'])->latest()->take(5)->get()
            ];
            return view('admin.dashboard', $data);
        }

        // 2. Dashboard User/Mitra (Katalog)
        $daftarKota = Branch::select('kota')->distinct()->pluck('kota');
        $query = Mobil::with(['rental', 'branch'])
            ->where('status', 'tersedia')
            ->whereHas('rental', fn($q) => $q->where('status', 'active'));

        if ($request->filled('kota')) {
            $query->whereHas('branch', fn($q) => $q->where('kota', $request->kota));
        }

        $mobils = $query->get();
        return view('dashboard', compact('mobils', 'daftarKota'));
    })->name('dashboard');

    // ==========================================
    // B. FITUR USER (KATALOG & TRANSAKSI)
    // ==========================================
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');
    Route::get('/tentang-kami', function () {
        return view('pages.tentang_kami', ['data' => TentangKami::all()]);
    })->name('pages.about');

    Route::prefix('order')->group(function () {
        // Fix: Mendefinisikan rute agar kedua nama dapat dikenali
        Route::get('/form', [PageController::class, 'order'])->name('pages.order');
        Route::get('/form/create', [PageController::class, 'order'])->name('user.transaksi.create'); 
        
        Route::post('/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/riwayat', [TransaksiController::class, 'index'])->name('riwayat');
        Route::get('/riwayat/all', [TransaksiController::class, 'index'])->name('riwayat.index'); 
        Route::put('/{id}/batal', [TransaksiController::class, 'batalkanPesanan'])->name('transaksi.batal'); 
        Route::post('/{id}/upload', [TransaksiController::class, 'upload'])->name('transaksi.upload');
        Route::get('/{id}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
    });

    // ==========================================
    // C. CHATBOT GEMINI AI
    // ==========================================
    Route::prefix('bot')->name('chatbot.')->group(function () {
        Route::post('/send-message', [ChatbotController::class, 'sendMessage'])->name('send');
        Route::post('/auto-book', [ChatbotController::class, 'autoBook'])->name('book');
        Route::get('/check-cars', [ChatbotController::class, 'checkAvailability']);
    });

    // ==========================================
    // D. SUPER ADMIN EXCLUSIVE
    // ==========================================
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('mobils', MobilController::class);
        Route::resource('admin/tentang-kami', AdminTentangKamiController::class, ['names' => 'admin.tentang_kami']);
        Route::resource('admin/branches', AdminBranchController::class)->names('admin.branches');

        Route::prefix('admin')->name('admin.')->group(function() {
            // Audit Pesanan
            Route::controller(AdminTransaksiController::class)->group(function() {
                Route::get('/transaksi', 'index')->name('transaksi.index');
                Route::patch('/transaksi/{id}/approve', 'approve')->name('transaksi.approve');
                Route::patch('/transaksi/{id}/reject', 'reject')->name('transaksi.reject');
                Route::patch('/transaksi/{id}/complete', 'complete')->name('transaksi.complete');
            });

            // Manajemen Mitra
            Route::controller(AdminRentalController::class)->group(function() {
                Route::get('/rentals', 'index')->name('rentals.index');
                Route::patch('/rentals/{id}/approve', 'approve')->name('rentals.approve');
                Route::patch('/rentals/{id}/block', 'block')->name('rentals.block');
                Route::delete('/rentals/{id}', 'destroy')->name('rentals.destroy');
            });
        });
    });

    // ==========================================
    // E. MITRA / VENDOR
    // ==========================================
Route::middleware(['auth', IsMitra::class])->prefix('mitra')->name('mitra.')->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');

    // Halaman Armada (Daftar Mobil)
    // Gunakan rute manual jika resource bikin pusing
    Route::get('/mobil', [MitraController::class, 'indexArmada'])->name('mobil.index');
    Route::get('/mobil/create', [MitraController::class, 'createArmada'])->name('mobil.create');
    Route::post('/mobil', [MitraController::class, 'storeArmada'])->name('mobil.store');
    Route::get('/mobil/{id}/edit', [MitraController::class, 'editArmada'])->name('mobil.edit');
    Route::put('/mobil/{id}', [MitraController::class, 'updateArmada'])->name('mobil.update');
    Route::delete('/mobil/{id}', [MitraController::class, 'destroyArmada'])->name('mobil.destroy');

    // Halaman Pesanan (Yang sudah kita perbaiki tadi)
    Route::get('/pesanan', [MitraController::class, 'indexPesanan'])->name('pesanan.index');
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