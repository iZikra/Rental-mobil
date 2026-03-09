<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mobil;
use App\Models\Branch;
use App\Models\Transaksi;
use App\Models\Rental;

class MitraController extends Controller
{
    /**
     * DASHBOARD UTAMA
     * Route: mitra.dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $rental = $user->rental;

        if (!$rental) {
            return redirect()->route('dashboard')->with('error', 'Data Rental belum ditemukan.');
        }

        // Statistik untuk Dashboard
        $totalMobil = Mobil::where('rental_id', $rental->id)->count();
        
        // Memperbaiki Undefined Variable $pesananAktif
        $pesananAktif = Transaksi::where('rental_id', $rental->id)
            ->whereIn('status', ['pending', 'Dikonfirmasi']) // Sesuaikan case-sensitive DB Anda
            ->count();

        $pendapatan = Transaksi::where('rental_id', $rental->id)
            ->where('status', 'Selesai')
            ->sum('total_harga');

        $pesananTerbaru = Transaksi::where('rental_id', $rental->id)
            ->latest()
            ->take(5)
            ->get();

        return view('mitra.dashboard', compact('rental', 'totalMobil', 'pesananAktif', 'pendapatan', 'pesananTerbaru'));
    }
public function selesaikanPesanan($id)
{
    $transaksi = Transaksi::findOrFail($id);
    
    DB::transaction(function () use ($transaksi) {
        $transaksi->update(['status' => 'Selesai']);
        
        // Kembalikan mobil ke status tersedia
        $transaksi->mobil->update(['status' => 'tersedia']);
    });

    return back()->with('success', 'Transaksi Selesai, Mobil siap disewakan kembali!');
}
    /**
     * DAFTAR ARMADA (MOBIL)
     * Route: mitra.mobil.index
     */
    public function indexArmada()
    {
        $user = Auth::user();
        $rental = $user->rental;

        if (!$rental) return redirect()->route('mitra.dashboard');

        // Gunakan with('branch') agar tidak n+1 query saat panggil lokasi
        $mobils = Mobil::where('rental_id', $rental->id)
            ->with('branch')
            ->latest()
            ->get();

        return view('mitra.mobil.index', compact('mobils', 'rental'));
    }

    public function createArmada()
    {
        $rental = Auth::user()->rental;
        if (!$rental) return redirect()->route('mitra.dashboard');

        $branches = Branch::where('rental_id', $rental->id)->get();
        return view('mitra.mobil.create', compact('branches'));
    }

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

        $imagePath = $request->file('gambar')->store('mobil_images', 'public');

        Mobil::create([
            'rental_id' => Auth::user()->rental->id,
            'branch_id' => $request->branch_id,
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

        return redirect()->route('mitra.mobil.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    /**
     * MANAJEMEN PESANAN
     */
    public function indexPesanan()
    {
        $user = Auth::user();
        if (!$user->rental) return redirect()->back()->with('error', 'Anda bukan mitra.');

        $pesanan = Transaksi::with(['mobil', 'user']) 
            ->where('rental_id', $user->rental->id)
            ->latest()
            ->get();

        return view('mitra.pesanan.index', compact('pesanan'));
    }

    public function konfirmasiPesanan($id)
{
    $user = Auth::user();
    
    // Gunakan Transaction agar perubahan terjadi serentak (Atomic)
    DB::beginTransaction();

    try {
        // 1. Cari transaksi
        $transaksi = Transaksi::where('id', $id)
            ->where('rental_id', $user->rental->id)
            ->firstOrFail();

        // 2. Update status transaksi menjadi Dikonfirmasi
        $transaksi->update(['status' => 'Dikonfirmasi']);

        // 3. LOGIC OTOMATIS: Ubah status mobil menjadi 'disewa' atau 'tidak tersedia'
        // Kita panggil relasi mobil dari transaksi tersebut
        if ($transaksi->mobil) {
            $transaksi->mobil->update([
                'status' => 'disewa' // Pastikan di database enum/string status mobil Anda mendukung 'disewa'
            ]);
        }

        DB::commit();
        return back()->with('success', 'Pesanan dikonfirmasi & Status mobil telah diperbarui menjadi disewa!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
    }
}

    public function tolakPesanan($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('rental_id', Auth::user()->rental->id)
            ->firstOrFail();

        $transaksi->update(['status' => 'Ditolak']);

        return redirect()->back()->with('success', 'Pesanan telah ditolak.');
    }
}