<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class AdminBranchController extends Controller
{
    // Menampilkan daftar wilayah/kota yang sudah ada
    public function index()
    {
        $branches = Branch::all();
        return view('admin.branches.index', compact('branches'));
    }

    // Form tambah wilayah (Opsional jika ingin pakai modal, abaikan ini jika satu halaman)
    public function create()
    {
        return view('admin.branches.create');
    }

    // Menyimpan data kota baru ke tabel branches
    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'nomor_telepon_cabang' => 'required|string',
        ]);

        // Karena ini Master Data, rental_id bisa dikosongkan atau diisi 0 
        // jika tabel Anda mengizinkan null.
        Branch::create($request->all());

        return redirect()->route('admin.branches.index')->with('success', 'Wilayah baru berhasil ditambahkan!');
    }

    // Menghapus wilayah
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return back()->with('success', 'Wilayah berhasil dihapus!');
    }
}