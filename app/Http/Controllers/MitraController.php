<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;

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
            ->whereIn('status', ['pending', 'disetujui'])
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
    $transaksi = \App\Models\Transaksi::with('mobil')->findOrFail($id);

    // 1. TAMENG OTORISASI MULTI-TENANT
    $isOwnerPusat = (isset($user->rental) && $user->rental->id == $transaksi->mobil->rental_id) || 
                    ($user->rental_id == $transaksi->mobil->rental_id);
    
    $isAdminCabang = (isset($user->branch_id) && $user->branch_id == $transaksi->branch_id) || 
                     (isset($user->branch_id) && $user->branch_id == $transaksi->mobil->branch_id);

    if (!$isOwnerPusat && !$isAdminCabang) {
        return back()->with('error', 'Akses ditolak: Pesanan ini bukan kewenangan cabang Anda.');
    }

    // 2. EKSEKUSI KONFIRMASI
    $transaksi->update(['status' => 'Disetujui']); 

    return redirect()->back()->with('success', 'Pesanan berhasil disetujui. Menunggu penyewa mengambil unit.');
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

    return redirect()->back()->with('success', 'Pesanan telah tegas ditolak.');
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
        'no_plat' => 'required|unique:mobils,no_plat',
        'branch_id' => 'required|exists:branches,id',
        'harga_sewa' => 'required|numeric',
        'tahun_buat' => 'required|integer',
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

    Mobil::create([
        'rental_id' => $rental_id,
        'branch_id' => $branch_id,
        'merk' => $request->merk,
        'model' => $request->model,
        'no_plat' => $request->no_plat,
        'harga_sewa' => $request->harga_sewa,
        'tahun_buat' => $request->tahun_buat,
        'transmisi' => $request->transmisi ?? 'Manual',
        'bahan_bakar' => $request->bahan_bakar ?? 'Bensin',
        'jumlah_kursi' => $request->jumlah_kursi ?? 4,
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
}