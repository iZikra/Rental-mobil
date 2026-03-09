<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Manajemen Master Wilayah & Cabang') }}
            </h2>
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                <i class="fas fa-plus mr-2"></i> Tambah Wilayah Baru
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                            <th class="px-6 py-4 font-bold">Nama Cabang</th>
                            <th class="px-6 py-4 font-bold">Kota</th>
                            <th class="px-6 py-4 font-bold">Alamat Lengkap</th>
                            <th class="px-6 py-4 font-bold">Telepon</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @forelse($branches as $branch)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium">{{ $branch->nama_cabang }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $branch->kota }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ Str::limit($branch->alamat_lengkap, 50) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $branch->nomor_telepon_cabang }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Hapus wilayah ini? Ini akan berdampak pada mobil yang terhubung.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data wilayah. Silakan tambah data baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-slate-900/50 hidden backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Tambah Master Wilayah</h3>
            
            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Cabang / Wilayah</label>
                        <input type="text" name="nama_cabang" placeholder="Contoh: Cabang Pusat" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Kota</label>
                        <input type="text" name="kota" placeholder="Contoh: Jakarta" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="3" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon_cabang" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:text-slate-800 transition">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>