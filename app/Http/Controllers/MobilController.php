<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahan untuk hapus gambar lama

class MobilController extends Controller
{
    /**
     * Menampilkan daftar mobil (Halaman Admin).
     */
    public function index()
{
    $user = Auth::user();

    if ($user->role === 'admin') {
        // Admin melihat semua mobil di database
        $mobils = Mobil::with('rental')->get();
    } 
    elseif ($user->role === 'vendor') {
        // Mitra HANYA melihat mobil miliknya
        // Mencegah Mitra A mengintip data Mitra B
        $rentalId = $user->rental->id;
        $mobils = Mobil::where('rental_id', $rentalId)->get();
    }

    return view('dashboard.mobil.index', compact('mobils'));
}

    /**
     * Menampilkan form tambah mobil baru.
     */
    public function create()
    {
        return view('admin.mobil.create');
    }

    /**
     * PERBAIKAN UTAMA: Menyimpan data mobil + Upload Gambar
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $data = $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'no_plat' => 'required|string|max:20|unique:mobils,no_plat',
            'harga_sewa' => 'required|numeric',
            'status' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
            'deskripsi' => 'nullable|string',
        ]);

        // 2. Logika Upload Gambar
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'storage/app/public/mobils'
            // Fungsi store() akan mengembalikan path (misal: mobils/foto1.jpg)
            $data['gambar'] = $request->file('gambar')->store('mobils', 'public');
        }

        // 3. Simpan ke Database
        Mobil::create($data);

        // 4. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('mobils.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    /**
     * Menghapus data mobil & gambarnya
     */
    public function destroy(Mobil $mobil)
    {
        // Hapus gambar fisik jika ada
        if ($mobil->gambar && Storage::disk('public')->exists($mobil->gambar)) {
            Storage::disk('public')->delete($mobil->gambar);
        }

        // Hapus data di database
        $mobil->delete();

        return redirect()->route('mobils.index')->with('success', 'Data mobil berhasil dihapus');
    }
    public function edit($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('admin.mobil.edit', compact('mobil'));
    }
    public function update(Request $request, $id)
    {
        $mobil = Mobil::findOrFail($id);

        // Validasi
        $request->validate([
            'merk'  => 'required',
            'model' => 'required',
            'no_plat' => 'required',
            'harga_sewa' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Cek jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            
            // Simpan ke folder public/img
            $file->move(public_path('img'), $nama_file);
            
            $data['gambar'] = $nama_file;
        }

        $mobil->update($data);

        return redirect()->route('mobils.index')->with('success', 'Data mobil berhasil diperbarui!');
    }
}