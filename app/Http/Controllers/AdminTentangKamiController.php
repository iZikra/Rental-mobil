<?php

namespace App\Http\Controllers;

use App\Models\TentangKami;
use Illuminate\Http\Request;

class AdminTentangKamiController extends Controller
{
    // 1. Tampilkan Daftar
    public function index()
    {
        $data = TentangKami::all();
        return view('admin.tentang_kami.index', compact('data'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('admin.tentang_kami.create');
    }

    // 3. Simpan Data Baru
    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        TentangKami::create($request->all());

        // UBAH DISINI: Redirect ke halaman publik (pages.about)
        return redirect()->route('pages.about')->with('success', 'Konten berhasil ditambahkan!');
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        $item = TentangKami::findOrFail($id);
        $item->update($request->all());

        // UBAH DISINI
        return redirect()->route('pages.about')->with('success', 'Konten berhasil diperbarui!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $item = TentangKami::findOrFail($id);
        $item->delete();

        // UBAH DISINI
        return redirect()->route('pages.about')->with('success', 'Konten berhasil dihapus!');
    }
}