<?php

namespace App\Http\Controllers; // <--- Namespace yang benar

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mobil;      // <--- WAJIB ADA
use App\Models\Branch;     // <--- WAJIB ADA
use App\Models\Transaksi;  // <--- WAJIB ADA

class MitraController extends Controller
{
    // === DASHBOARD UTAMA ===
    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah User sudah punya profil Rental
        if (!$user->rental) {
            // Kita arahkan ke dashboard biasa jika belum punya rental, atau tampilkan pesan
            return redirect()->route('dashboard')->with('error', 'Akun Anda terdaftar sebagai Mitra, namun Data Rental belum ditemukan.');
        }

        $rental = $user->rental;

        // Statistik untuk Dashboard
        $totalMobil = $rental->mobils()->count();
        $pesananAktif = $rental->transaksis()->whereIn('status', ['pending', 'dibayar', 'dikonfirmasi'])->count();
        $pendapatan = $rental->transaksis()->where('status', 'selesai')->sum('total_harga');

        return view('mitra.dashboard', compact('rental', 'totalMobil', 'pesananAktif', 'pendapatan'));
    }

    // === MANAJEMEN MOBIL (Hanya Mobil Milik Sendiri) ===
    public function indexMobil()
    {
        if (!Auth::user()->rental) return redirect()->route('mitra.dashboard');

        $rentalId = Auth::user()->rental->id;
        
        // Query: Ambil mobil DIMANA rental_id = rental saya
        $mobils = Mobil::where('rental_id', $rentalId)->with('branch')->latest()->get();

        return view('mitra.mobil.index', compact('mobils'));
    }

    public function createMobil()
    {
        if (!Auth::user()->rental) return redirect()->route('mitra.dashboard');

        // Kita butuh data cabang untuk dropdown (Pilih Lokasi Mobil)
        $branches = Branch::where('rental_id', Auth::user()->rental->id)->get();
        return view('mitra.mobil.create', compact('branches'));
    }

    public function storeMobil(Request $request)
    {
        $request->validate([
            'merk' => 'required|string',
            'model' => 'required|string',
            'no_plat' => 'required|unique:mobils,no_plat',
            'branch_id' => 'required|exists:branches,id',
            'harga_sewa' => 'required|numeric',
            'tahun_buat' => 'required|integer',
            'transmisi' => 'required',
            'bahan_bakar' => 'required',
            'jumlah_kursi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('mobil_images', 'public');
        }

        // Simpan ke Database
        Mobil::create([
            'rental_id' => Auth::user()->rental->id,
            'branch_id' => $request->branch_id,
            'merk' => $request->merk,
            'model' => $request->model,
            'no_plat' => $request->no_plat,
            'harga_sewa' => $request->harga_sewa,
            'tahun_buat' => $request->tahun_buat,
            'transmisi' => $request->transmisi,
            'bahan_bakar' => $request->bahan_bakar,
            'jumlah_kursi' => $request->jumlah_kursi,
            'gambar' => $imagePath,
            'status' => 'tersedia',
        ]);

        return redirect()->route('mitra.mobil.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    // === MANAJEMEN PESANAN ===
    public function indexPesanan()
    {
        if (!Auth::user()->rental) return redirect()->route('mitra.dashboard');

        $rentalId = Auth::user()->rental->id;
        
        // Ambil transaksi yang masuk ke rental ini saja
        $transaksis = Transaksi::where('rental_id', $rentalId)
                        ->with(['user', 'mobil'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('mitra.pesanan.index', compact('transaksis'));
    }

    public function konfirmasiPesanan(Request $request, Transaksi $transaksi)
    {
        // Validasi Keamanan: Pastikan transaksi ini milik rental saya
        if ($transaksi->rental_id !== Auth::user()->rental->id) {
            abort(403, 'Akses Ilegal');
        }

        $transaksi->update(['status' => 'dikonfirmasi']);
        
        return back()->with('success', 'Pesanan dikonfirmasi!');
    }
    // --- TAMBAHAN: FUNGSI EDIT & UPDATE ---

    public function editMobil(Mobil $mobil)
    {
        // 1. Validasi Keamanan: Pastikan mobil ini milik Rental User yg login
        if ($mobil->rental_id !== Auth::user()->rental->id) {
            abort(403, 'Akses Ditolak: Ini bukan mobil Anda.');
        }

        // 2. Ambil semua cabang untuk dropdown
        $branches = Branch::where('rental_id', Auth::user()->rental->id)->get();

        return view('mitra.mobil.edit', compact('mobil', 'branches'));
    }

    public function updateMobil(Request $request, Mobil $mobil)
    {
        // 1. Validasi Keamanan
        if ($mobil->rental_id !== Auth::user()->rental->id) {
            abort(403, 'Akses Ditolak');
        }

        // 2. Validasi Input
        $request->validate([
            'merk' => 'required|string',
            'model' => 'required|string',
            // Pengecualian unik untuk mobil ini sendiri (ignore id)
            'no_plat' => 'required|unique:mobils,no_plat,' . $mobil->id,
            'branch_id' => 'required|exists:branches,id',
            'harga_sewa' => 'required|numeric',
            'tahun_buat' => 'required|integer',
            'transmisi' => 'required',
            'bahan_bakar' => 'required',
            'jumlah_kursi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable (boleh kosong)
        ]);

        // 3. Cek apakah ada upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada (opsional, biar hemat storage)
            // if ($mobil->gambar) Storage::delete('public/' . $mobil->gambar);

            // Simpan gambar baru
            $imagePath = $request->file('gambar')->store('mobil_images', 'public');
            $mobil->gambar = $imagePath;
        }

        // 4. Update Data Lainnya
        $mobil->update([
            'branch_id' => $request->branch_id, // Bisa pindah cabang
            'merk' => $request->merk,
            'model' => $request->model,
            'no_plat' => $request->no_plat,
            'harga_sewa' => $request->harga_sewa,
            'tahun_buat' => $request->tahun_buat,
            'transmisi' => $request->transmisi,
            'bahan_bakar' => $request->bahan_bakar,
            'jumlah_kursi' => $request->jumlah_kursi,
            // Status tidak diupdate disini, ada fitur tersendiri biasanya
        ]);

        return redirect()->route('mitra.mobil.index')->with('success', 'Data mobil berhasil diperbarui!');
    }
}