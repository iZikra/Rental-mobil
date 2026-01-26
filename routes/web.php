<?php

// CHATBOT ROUTES
use App\Http\Controllers\ChatbotController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

// --- IMPORT CONTROLLER ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MobilController;

// KITA GUNAKAN 2 CONTROLLER INI UNTUK TRANSAKSI:
use App\Http\Controllers\TransaksiController;      // Untuk User
use App\Http\Controllers\AdminTransaksiController; // Untuk Admin
use App\Http\Controllers\AdminTentangKamiController;

use App\Models\Mobil;
use App\Models\TentangKami;
use App\Models\Transaksi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Redirect Halaman Awal ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- GRUP RUTE UTAMA (Harus Login) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // A. DASHBOARD (Logic Admin vs User)
    // ==========================================
    Route::get('/dashboard', function () {
        // Cek Role Admin
        if (Auth::user()->role === 'admin') { 
            
            // Logika Statistik Admin
            $totalMobil    = Mobil::count();
            $mobilTersedia = Mobil::where('status', 'tersedia')->count();
            $transaksiBaru = Transaksi::where('status', 'Pending')->count();
            $pendapatan    = Transaksi::where('status', 'Selesai')->sum('total_harga');
            $recentOrders  = Transaksi::with('user', 'mobil')->latest()->take(5)->get();

            return view('admin.dashboard', compact('totalMobil', 'mobilTersedia', 'transaksiBaru', 'pendapatan', 'recentOrders'));
        }

        // JIKA BUKAN ADMIN (User Biasa)
        $mobils = Mobil::where('status', 'tersedia')->get();
        return view('dashboard', compact('mobils'));

    })->name('dashboard');


    // ==========================================
    // B. MENU USER (FRONTEND)
    // ==========================================
    Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');
    Route::get('/form-order', [PageController::class, 'order'])->name('pages.order');
    
    // Halaman Tentang Kami
    Route::get('/tentang-kami', function () {
        $data = TentangKami::all(); 
        return view('pages.tentang_kami', compact('data'));
    })->name('pages.about');

    Route::get('/riwayat/{id}/cetak', [TransaksiController::class, 'cetak'])->name('riwayat.cetak');

    // ==========================================
    // C. TRANSAKSI & RIWAYAT (USER)
    // ==========================================
    
    // 1. Proses Simpan Order Baru
    Route::post('/order/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');
    
    // 2. Halaman Riwayat (Menggunakan TransaksiController@index)
    Route::get('/riwayat-order', [TransaksiController::class, 'index'])->name('riwayat');
    
    // 3. Batalkan Pesanan
    Route::put('/transaksi/{id}/batal', [TransaksiController::class, 'batal'])->name('transaksi.batal'); 

    // 4. Upload Bukti Bayar (WAJIB POST AGAR TIDAK ERROR METHOD NOT ALLOWED)
    // Kita arahkan ke fungsi 'upload' di TransaksiController
    Route::post('/riwayat/{id}/upload', [TransaksiController::class, 'upload'])->name('riwayat.upload');

    Route::get('/order', [TransaksiController::class, 'index'])->name('order');

    // D. CHATBOT
    // ==========================================

    Route::middleware(['auth'])->group(function () {
    // API CHATBOT
    Route::post('/bot/auto-book', [ChatbotController::class, 'autoBook'])->name('bot.book');
    // === API CHATBOT ===
    // 1. Cek Ketersediaan (Fitur Baru)

    Route::get('/bot/check-cars', [App\Http\Controllers\ChatbotController::class, 'checkAvailability']);
    
    // 2. Auto Booking (Fitur Baru)
    Route::post('/bot/auto-book', [ChatbotController::class, 'autoBook'])->name('bot.book');

    // 3. Chat Teks / LLM (INI YANG KURANG)
    Route::post('/bot/send-message', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    Route::get('/transaksi/buat', [TransaksiController::class, 'create'])->name('user.transaksi.create');
    
    // Rute untuk menyimpan data (jika belum ada)
    Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('user.transaksi.store');
});

    // ==========================================
    // E. MENU KHUSUS ADMIN (Middleware Admin)
    // ==========================================
    // Pastikan Middleware ini sesuai dengan kernel Anda (bisa 'admin' atau class lengkap)
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        
        // 1. Kelola Mobil (CRUD Lengkap)
        Route::resource('mobils', MobilController::class);

        // 2. Kelola Transaksi (Approval)
        Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index'])->name('admin.transaksi.index');
        Route::patch('/admin/transaksi/{id}/approve', [AdminTransaksiController::class, 'approve'])->name('admin.transaksi.approve');
        Route::patch('/admin/transaksi/{id}/reject', [AdminTransaksiController::class, 'reject'])->name('admin.transaksi.reject');
        Route::patch('/admin/transaksi/{id}/complete', [AdminTransaksiController::class, 'complete'])->name('admin.transaksi.complete');

        // 3. Kelola Halaman Tentang Kami
        Route::resource('admin/tentang-kami', AdminTentangKamiController::class, [
            'names' => 'admin.tentang_kami'
        ]);

    }); 


    // ==========================================
    // F. PROFILE SETTINGS
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// ROUTE BACA GAMBAR (OPSIONAL)
// ==========================================
// Jika storage:link sudah dijalankan, rute ini sebenarnya tidak wajib.
// Tapi kita biarkan untuk jaga-jaga jika symlink tidak jalan.
Route::get('/tampilkan-gambar/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->name('storage.view');
Route::get('/cek-model', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    // Tembak langsung ke Google untuk minta daftar model
    $response = \Illuminate\Support\Facades\Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    if ($response->successful()) {
        return $response->json();
    } else {
        return "Gagal Konek: " . $response->body();
    }
});

require __DIR__.'/auth.php';