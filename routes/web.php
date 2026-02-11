<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http; // Tambahkan ini untuk HTTP client
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// --- IMPORT CONTROLLER ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\TransaksiController;      
use App\Http\Controllers\AdminTransaksiController; 
use App\Http\Controllers\AdminTentangKamiController;
use App\Http\Controllers\ChatbotController;

use App\Models\Mobil;
use App\Models\TentangKami;
use App\Models\Transaksi;
use App\Models\Branch;
use App\Models\User;
use App\Models\Rental;

/* MITRA */
use App\Http\Controllers\MitraController;
use App\Http\Middleware\IsMitra;

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
    // A. DASHBOARD
    // ==========================================
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') { 
            $totalMobil    = Mobil::count();
            $mobilTersedia = Mobil::where('status', 'tersedia')->count();
            $transaksiBaru = Transaksi::where('status', 'Pending')->count();
            $pendapatan    = Transaksi::where('status', 'Selesai')->sum('total_harga');
            $recentOrders  = Transaksi::with('user', 'mobil')->latest()->take(5)->get();

            return view('admin.dashboard', compact('totalMobil', 'mobilTersedia', 'transaksiBaru', 'pendapatan', 'recentOrders'));
        }

        // USER BIASA
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


    // ==========================================
// C. TRANSAKSI & RIWAYAT (USER)
// ==========================================

// 1. Simpan Order
Route::post('/order/simpan', [TransaksiController::class, 'store'])->name('transaksi.store');

// 2. Halaman Riwayat
Route::get('/riwayat-order', [TransaksiController::class, 'index'])->name('riwayat');
Route::get('/riwayat-order/index', [TransaksiController::class, 'index'])->name('riwayat.index'); 

// 3. Batalkan Pesanan (PERBAIKAN: Hubungkan ke fungsi batalkanPesanan)
// Pastikan memanggil 'batalkanPesanan', bukan 'batal'
Route::put('/transaksi/{id}/batal', [TransaksiController::class, 'batalkanPesanan'])->name('transaksi.batal'); 

// 4. Upload Bukti Bayar
Route::post('/transaksi/{id}/upload', [TransaksiController::class, 'upload'])->name('transaksi.upload');

// 5. Cetak Tiket
Route::get('/riwayat/{id}/cetak', [TransaksiController::class, 'cetak'])->name('riwayat.cetak');
Route::get('/transaksi/{id}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
    


    // ==========================================
    // D. CHATBOT
    // ==========================================
    Route::post('/bot/auto-book', [ChatbotController::class, 'autoBook'])->name('bot.book');
    Route::get('/bot/check-cars', [ChatbotController::class, 'checkAvailability']);
    Route::post('/bot/send-message', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    // Route khusus pembuatan transaksi dari Chatbot
    Route::get('/transaksi/buat', [TransaksiController::class, 'create'])->name('user.transaksi.create');
    Route::post('/transaksi/simpan-bot', [TransaksiController::class, 'store'])->name('user.transaksi.store');


    // ==========================================
    // E. MENU KHUSUS ADMIN
    // ==========================================
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        
        // 1. Kelola Mobil
        Route::resource('mobils', MobilController::class);

        // 2. Kelola Transaksi
        Route::get('/admin/transaksi', [AdminTransaksiController::class, 'index'])->name('admin.transaksi.index');
        Route::patch('/admin/transaksi/{id}/approve', [AdminTransaksiController::class, 'approve'])->name('admin.transaksi.approve');
        Route::patch('/admin/transaksi/{id}/reject', [AdminTransaksiController::class, 'reject'])->name('admin.transaksi.reject');
        Route::patch('/admin/transaksi/{id}/complete', [AdminTransaksiController::class, 'complete'])->name('admin.transaksi.complete');

        // 3. Kelola Tentang Kami
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
// TOOLS UTILITIES
// ==========================================
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

// Cek Koneksi Gemini AI
Route::get('/cek-model', function () {
    $apiKey = env('GEMINI_API_KEY');
    $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    if ($response->successful()) {
        return $response->json();
    } else {
        return "Gagal Konek: " . $response->body();
    }
});

/* MITRA RUTE */
Route::middleware(['auth', IsMitra::class])->prefix('mitra')->name('mitra.')->group(function () {
    
    Route::get('/dashboard', [MitraController::class, 'index'])->name('dashboard');

    Route::get('/mobil', [MitraController::class, 'indexMobil'])->name('mobil.index');
    Route::get('/mobil/create', [MitraController::class, 'createMobil'])->name('mobil.create');
    Route::post('/mobil', [MitraController::class, 'storeMobil'])->name('mobil.store');
    
    Route::get('/pesanan', [MitraController::class, 'indexPesanan'])->name('pesanan.index');
    Route::post('/pesanan/{transaksi}/konfirmasi', [MitraController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');

    Route::get('/mobil/{mobil}/edit', [MitraController::class, 'editMobil'])->name('mobil.edit');
    Route::put('/mobil/{mobil}', [MitraController::class, 'updateMobil'])->name('mobil.update');
});

Route::get('/debug/buat-mitra-baru', function () {
    
    // 1. Buat User Baru (Role: Vendor)
    $namaMitra = 'Mitra Sejahtera Abadi';
    $emailMitra = 'mitra2@fzrent.com'; // Ganti email jika mau bikin lagi
    $password = 'password';

    // Cek dulu biar gak duplikat
    if(User::where('email', $emailMitra)->exists()) {
        return "ERROR: User dengan email $emailMitra sudah ada!";
    }

    $user = User::create([
        'name' => $namaMitra,
        'email' => $emailMitra,
        'password' => Hash::make($password),
        'role' => 'vendor', // PENTING: Role harus vendor
        'no_hp' => '081234567890',
        'alamat' => 'Jl. Merdeka No. 45',
        'sim_a' => null,
        'ktp' => null,
    ]);

    // 2. Buat Data Rental (Wajib ada biar bisa akses dashboard)
    $rental = Rental::create([
        'user_id' => $user->id,
        'nama_rental' => $namaMitra,
        'slug' => Str::slug($namaMitra),
        'no_telp_bisnis' => '081234567890',
        'deskripsi' => 'Rental mobil terpercaya dan amanah.',
        'alamat' => 'Jl. Merdeka No. 45', // Pastikan kolom ini ada di migrasi rentals
        'status' => 'active', // Langsung aktif
    ]);

    // 3. Buat Cabang Utama (Wajib ada untuk tambah mobil)
    Branch::create([
        'rental_id' => $rental->id,
        'nama_cabang' => 'Cabang Pusat',
        'kota' => 'Jakarta',
        'alamat_lengkap' => 'Jl. Merdeka No. 45, Jakarta Pusat',
        'nomor_telepon_cabang' => '021-555555',
    ]);

    return "BERHASIL! <br> 
            Akun Mitra Baru Terbuat. <br>
            Email: <b>$emailMitra</b> <br>
            Pass: <b>$password</b> <br>
            <a href='/login'>Klik disini untuk Login</a>";
});

require __DIR__.'/auth.php';