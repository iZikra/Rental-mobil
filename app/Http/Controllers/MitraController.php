<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MitraController extends Controller
{

    /**
     * DASHBOARD MITRA
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Jika user adalah cabang
        if ($user->branch_id) {
            $branch = Branch::find($user->branch_id);

            if (!$branch) {
                abort(403, 'Cabang tidak ditemukan');
            }

            $rental = $branch->rental;
        } 
        // Jika user owner rental
        else {
            $rental = $user->rental;
        }

        if (!$rental) {
            abort(403, 'Data rental tidak ditemukan');
        }

        $user = Auth::user();

if ($user->branch_id) {
    // Jika user cabang
    $totalMobil = Mobil::where('branch_id', $user->branch_id)->count();
} else {
    // Jika owner rental
    $totalMobil = Mobil::where('rental_id', $rental->id)->count();
}

        $pesananAktif = Transaksi::where('rental_id', $rental->id)
            ->whereIn('status', ['Pending', 'Disewa'])
            ->count();

        $pendapatan = Transaksi::where('rental_id', $rental->id)
            ->where('status', 'Selesai')
            ->sum('total_harga');

        $pesananTerbaru = Transaksi::where('rental_id', $rental->id)
            ->with(['user', 'mobil'])
            ->latest()
            ->take(5)
            ->get();

        return view('mitra.dashboard', compact(
            'rental',
            'totalMobil',
            'pesananAktif',
            'pendapatan',
            'pesananTerbaru'
        ));
    }

    public function konfirmasiPesanan($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // PERBAIKAN MUTLAK: Tambahkan relasi 'user' agar sistem bisa membaca nomor HP penyewa
        $transaksi = \App\Models\Transaksi::with(['mobil', 'user'])->findOrFail($id);

        // 1. TAMENG OTORISASI MULTI-TENANT
        $isOwnerPusat = (isset($user->rental) && $user->rental->id == $transaksi->mobil->rental_id) || 
                        ($user->rental_id == $transaksi->mobil->rental_id);
        
        $isAdminCabang = (isset($user->branch_id) && $user->branch_id == $transaksi->branch_id) || 
                         (isset($user->branch_id) && $user->branch_id == $transaksi->mobil->branch_id);

        if (!$isOwnerPusat && !$isAdminCabang) {
            return back()->with('error', 'Akses ditolak: Pesanan ini bukan kewenangan cabang Anda.');
        }

        // 2. EKSEKUSI KONFIRMASI (Update Database)
        $transaksi->update(['status' => 'Disewa']); 

        // Update status mobil menjadi 'disewa'
        $mobil = Mobil::find($transaksi->mobil_id);
        if ($mobil) {
            $mobil->update(['status' => 'disewa']);
        }

        // 3. LOGIKA PENGIRIMAN NOTIFIKASI WHATSAPP
        $noHpPenyewa = $transaksi->no_hp ?? $transaksi->user->no_hp; 
        $namaPenyewa = $transaksi->user->name;
        $namaMobil = $transaksi->mobil->merk . ' ' . $transaksi->mobil->model;
        
        if (empty($noHpPenyewa)) {
            \Illuminate\Support\Facades\Log::warning("WA Dibatalkan: Nomor HP KOSONG untuk Transaksi ID: {$id}");
            return redirect()->back()->with('success', 'Pesanan disetujui, TETAPI notifikasi WA tidak terkirim karena akun Penyewa tidak memiliki nomor WhatsApp.');
        }

        // Rakit Pesan
        $teksPesan = "*NOTIFIKASI FZ RENT CAR*\n\n"
                   . "Halo {$namaPenyewa},\n"
                   . "Kabar baik! Permohonan sewa armada *{$namaMobil}* Anda telah *DISETUJUI* oleh Mitra kami.\n\n"
                   . "Total Tagihan: *Rp " . number_format($transaksi->total_harga ?? 0, 0, ',', '.') . "*\n\n"
                   . "Silakan login kembali ke website untuk melihat detail dan melakukan pembayaran.\n"
                   . "Terima kasih!";

        // Tembak API Fonnte
        try {
            $response = Http::withHeaders([
                'Authorization' => env('WA_API_TOKEN'), // Diambil dari file .env
            ])->asForm()->post(env('WA_API_URL'), [
                'target' => $noHpPenyewa, 
                'message' => $teksPesan,
                'countryCode' => '62', // Otomatis mengonversi 08... menjadi 628...
            ]);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] === true) {
                Log::info('WA Sukses dikirim ke: ' . $noHpPenyewa . ' | Status: ' . json_encode($result));
            } else {
                Log::error('WA Gagal (Dari Vendor Fonnte): ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Koneksi WA API Putus: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pesanan disetujui & Notifikasi WA telah dikirim ke nomor ' . $noHpPenyewa);
    }

public function tolakPesanan($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $transaksi = \App\Models\Transaksi::with('mobil')->findOrFail($id);

        // 1. TAMENG OTORISASI MULTI-TENANT
        $isOwnerPusat = (isset($user->rental) && $user->rental->id == $transaksi->mobil->rental_id) || 
                        ($user->rental_id == $transaksi->mobil->rental_id);
        
        $isAdminCabang = (isset($user->branch_id) && $user->branch_id == $transaksi->branch_id) || 
                         (isset($user->branch_id) && $user->branch_id == $transaksi->mobil->branch_id);

        if (!$isOwnerPusat && !$isAdminCabang) {
            return back()->with('error', 'Akses ditolak: Pesanan ini bukan kewenangan cabang Anda.');
        }

        // 2. EKSEKUSI PENOLAKAN
        $transaksi->update(['status' => 'Ditolak']); 

        // --- TAMBAHAN KODE MUTLAK ---
        // Bebaskan kembali mobil ke etalase karena pesanan dibatalkan sepihak oleh Mitra
        $transaksi->mobil->update([
            'status' => 'tersedia'
        ]);
        // ----------------------------

        return redirect()->back()->with('success', 'Pesanan telah tegas ditolak dan unit kembali tersedia di etalase.');
    }
        /**
     * SELESAIKAN PESANAN
     */
public function selesaikanPesanan($id)
{
    $user = Auth::user();
    
    // 1. Cari transaksinya dulu beserta data mobilnya (Jangan di-filter di sini agar tidak langsung 404)
    $transaksi = Transaksi::with('mobil')->findOrFail($id);

    // 2. LOGIKA OTORISASI MULTI-TENANT (Owner Pusat ATAU Admin Cabang)
    
    // Cek Akses 1: Apakah user adalah Owner Pusat? (Mengecek rental_id)
    $isOwnerPusat = (isset($user->rental) && $user->rental->id == $transaksi->mobil->rental_id) || 
                    ($user->rental_id == $transaksi->mobil->rental_id);
    
    // Cek Akses 2: Apakah user adalah Admin Cabang? (Mengecek branch_id)
    $isAdminCabang = (isset($user->branch_id) && $user->branch_id == $transaksi->branch_id) || 
                     (isset($user->branch_id) && $user->branch_id == $transaksi->mobil->branch_id);

    // Jika user BUKAN Owner Pusat DAN BUKAN Admin Cabang dari mobil tersebut, tolak!
    if (!$isOwnerPusat && !$isAdminCabang) {
        return back()->with('error', 'Akses ditolak: Transaksi ini bukan milik armada cabang atau rental Anda.');
    }

    // 3. Eksekusi Perubahan Status jika lolos otorisasi
    DB::beginTransaction();
    try {
        // Update status transaksi
        $transaksi->update(['status' => 'Selesai']);

        // Update status mobil secara paksa via DB Table
        $affected = DB::table('mobils')
            ->where('id', $transaksi->mobil_id)
            ->update(['status' => 'tersedia']);

        if ($affected === 0) {
            Log::warning("Peringatan: Tidak ada baris di tabel mobils yang diupdate untuk Transaksi ID: {$id}");
        }

        DB::commit();
        
        // Ingat: Mobil ini sekarang tersedia dan datanya bisa dibaca oleh Chatbot RAG. 
        // (Sesuai instruksi Anda sebelumnya, Chatbot hanya akan memberikan info stok ini, bukan melakukan booking).
        return redirect()->back()->with('success', 'Berhasil! Mobil kini tersedia kembali di sistem.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Gagal Selesaikan Pesanan: " . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}    
/**
     * LIST ARMADA
     */
    public function indexArmada()
{
    $user = Auth::user();

    if ($user->branch_id) {
        // cabang hanya melihat mobil cabangnya
        $mobils = Mobil::where('branch_id', $user->branch_id)
            ->with('branch')
            ->latest()
            ->get();

    } else {

        // owner melihat semua mobil rental
        $rental = $user->rental;

        $mobils = Mobil::where('rental_id', $rental->id)
            ->with('branch')
            ->latest()
            ->get();
    }

    return view('mitra.mobil.index', compact('mobils'));
}

    /**
     * FORM TAMBAH MOBIL
     */
    public function createArmada()
{
    $user = Auth::user();

    if ($user->branch_id) {

        // cabang hanya bisa pilih branch sendiri
        $branches = Branch::where('id', $user->branch_id)->get();

    } else {

        $rental = $user->rental;

        $branches = Branch::where('rental_id', $rental->id)->get();
    }

    return view('mitra.mobil.create', compact('branches'));
}
    /**
     * SIMPAN MOBIL
     */
    public function storeArmada(Request $request)
{
    $request->validate([
        'merk' => 'required|string',
        'model' => 'required|string',
        'tipe_mobil' => 'required|in:City Car,Compact MPV,Luxury Sedan,Mini MPV,Minibus,Minivan,SUV,Sedan',
        'branch_id' => 'required|exists:branches,id',
        'harga_sewa' => 'required|numeric',
        'tahun_buat' => 'required|integer',
        'transmisi' => 'required|in:matic,manual',
        'bahan_bakar' => 'required|in:Bensin,Solar,Listrik',
        'jumlah_kursi' => 'required|integer|min:2|max:20',
        'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = Auth::user();

    // Jika user cabang
    if ($user->branch_id) {
        $branch = Branch::findOrFail($user->branch_id);
        $rental_id = $branch->rental_id;
        $branch_id = $branch->id;
    }
    // Jika owner rental
    else {
        $rental = $user->rental;
        $rental_id = $rental->id;
        $branch_id = $request->branch_id;
    }

    $imagePath = $request->file('gambar')->store('mobil_images', 'public');

    do {
        $generatedNoPlat = 'UNIT-' . strtoupper(Str::random(10));
    } while (Mobil::where('no_plat', $generatedNoPlat)->exists());

    Mobil::create([
        'rental_id' => $rental_id,
        'branch_id' => $branch_id,
        'merk' => $request->merk,
        'model' => $request->model,
        'no_plat' => $generatedNoPlat,
        'harga_sewa' => $request->harga_sewa,
        'tipe_mobil' => $request->tipe_mobil,
        'tahun_buat' => $request->tahun_buat,
        'transmisi' => $request->transmisi,
        'bahan_bakar' => $request->bahan_bakar,
        'jumlah_kursi' => $request->jumlah_kursi,
        'gambar' => $imagePath,
        'status' => 'tersedia',
    ]);

    return redirect()->route('mitra.mobil.index')
        ->with('success', 'Mobil berhasil ditambahkan!');
}
    /**
     * LIST PESANAN
     */
    public function indexPesanan()
{
    $user = Auth::user();

    if ($user->branch_id) {
        // Optimasi: Tidak perlu find branch lagi jika hanya butuh ID-nya
        $pesanan = Transaksi::with(['mobil', 'user']) // Eager loading agar tidak berat
            ->where('branch_id', $user->branch_id)
            ->latest()
            ->get();
    } else {
        // Jika owner rental (Pastikan relasi 'rental' ada di model User)
        $rentalId = $user->rental_id ?? ($user->rental ? $user->rental->id : null);
        
        $pesanan = Transaksi::with(['mobil', 'user'])
            ->where('rental_id', $rentalId)
            ->latest()
            ->get();
    }

    // Kirim dengan nama 'pesanans'
    return view('mitra.pesanan.index', compact('pesanan'));
}
public function pengaturan()
{
    $user = \Illuminate\Support\Facades\Auth::user();
    $rental = $user->rental; // Mengambil data rental milik user yang login

    // Cek apakah user ini benar-benar pemilik rental pusat
    if (!$rental) {
        return redirect()->back()->with('error', 'Akses Ditolak: Hanya Pemilik Rental Pusat yang dapat mengubah pengaturan ini.');
    }

    return view('mitra.pengaturan', compact('rental'));
}

public function updatePengaturan(\Illuminate\Http\Request $request)
{

    $rental = \Illuminate\Support\Facades\Auth::user()->rental;

    if (!$rental) {
        return redirect()->back()->with('error', 'Akses Ditolak.');
    }

    $request->validate([
            'nama_rental'        => 'required|string|max:255',
            'alamat'             => 'required|string',
            'nama_bank'          => 'nullable|string|max:100',
            'no_rekening'        => 'nullable|string|max:100',
            'atas_nama_rekening' => 'nullable|string|max:255',
            'syarat_ketentuan'   => 'nullable|string',
            'biaya_sopir_per_hari' => 'nullable|integer|min:0',
            'biaya_bandara_per_trip' => 'nullable|integer|min:0',
    ]);

    // Update data ke database menggunakan kolom asli milik Anda
    $rental->update([
        'nama_rental'        => $request->nama_rental,
        'alamat'             => $request->alamat,
        'nama_bank'          => $request->nama_bank,
        'no_rekening'        => $request->no_rekening,
        'atas_nama_rekening' => $request->atas_nama_rekening,
        'syarat_ketentuan'   => $request->syarat_ketentuan,
        'biaya_sopir_per_hari' => (int) ($request->biaya_sopir_per_hari ?? 0),
        'biaya_bandara_per_trip' => (int) ($request->biaya_bandara_per_trip ?? 0),
    ]);

    return redirect()->back()->with('success', 'Pengaturan Rental, Rekening, dan Syarat Ketentuan berhasil diperbarui!');
}
// 1. Fungsi untuk membuka halaman form Edit
    public function editArmada($id)
    {
        // Cari mobil berdasarkan ID, jika tidak ada langsung munculkan error 404
        $mobil = \App\Models\Mobil::findOrFail($id);
        
        // Ambil daftar cabang untuk dropdown
        $branches = \App\Models\Branch::all(); 

        return view('mitra.mobil.edit', compact('mobil', 'branches'));
    }

    // 2. Fungsi untuk memproses update data ke database
    public function updateArmada(\Illuminate\Http\Request $request, $id)
    {
        $mobil = \App\Models\Mobil::findOrFail($id);

        // VALIDASI KETAT (Sama seperti fungsi store/create)
        $validatedData = $request->validate([
            'branch_id' => 'required',
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'tipe_mobil' => 'required|in:City Car,Compact MPV,Luxury Sedan,Mini MPV,Minibus,Minivan,SUV,Sedan',
            'tahun_buat' => 'required|integer|min:2000',
            'transmisi' => 'required|in:matic,manual',
            'bahan_bakar' => 'required|in:Bensin,Solar,Listrik',
            'jumlah_kursi' => 'required|integer|min:2',
            'harga_sewa' => 'required|numeric|min:0',
            // Gambar tidak wajib diisi saat edit. Hanya tervalidasi jika user mengupload gambar baru
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // EKSEKUSI UPDATE GAMBAR (Jika Mitra mengupload foto baru)
        if ($request->hasFile('gambar')) {
            if (!empty($mobil->gambar)) {
                if (str_contains($mobil->gambar, '/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($mobil->gambar);
                } else {
                    $oldImagePath = public_path('img/mobil/' . $mobil->gambar);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            $validatedData['gambar'] = $request->file('gambar')->store('mobil_images', 'public');
        }

        // Simpan pembaruan ke database
        $mobil->update($validatedData);

        // Redirect kembali ke halaman daftar armada (Sesuaikan nama route index Anda)
        return redirect()->route('mitra.mobil.index')->with('success', 'Data armada berhasil diperbarui!');
    }
}
