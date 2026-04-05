<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-tight">
                Armada Saya
            </h2>
            <a href="{{ route('mitra.mobil.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-xs font-black uppercase shadow-lg transition-all">
                + Tambah Mobil
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 p-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex gap-4">
                        <a href="{{ route('mitra.mobil.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            + Tambah Mobil Baru
                    </a>
                </div>
            </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase">Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase">Nama Mobil</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Harga/Hari</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($mobils as $m)
                        <tr class="hover:bg-blue-50/50 transition">
                            
                            {{-- FOTO MOBIL (Diambil Langsung Dari Database) --}}
            <td class="px-6 py-4 w-32 h-24">
                <img src="{{ $m->image_url }}"
                     alt="{{ $m->merk }} {{ $m->model }}"
                     class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-lg {{ $m->status != 'tersedia' ? 'grayscale opacity-70' : '' }}"
                     onerror="this.src='https://placehold.co/150x100?text=Tanpa+Foto'">
            </td>

                            {{-- NAMA & MERK --}}
                            <td class="px-6 py-4">
                                <div class="text-xl font-black text-gray-900 uppercase tracking-tight leading-none">
                                    {{ $m->model }}
                                </div>
                                <div class="text-xs font-bold text-blue-600 uppercase mt-1 tracking-widest">
                                    {{ $m->merk ?? $m->merek }} {{-- Mencegah error jika kolom bernama merek bukan merk --}}
                                </div>
                            </td>
                            
                            {{-- HARGA SEWA --}}
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-black text-blue-700">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</div>
                            </td>
                            
                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-[10px] font-black rounded-full {{ $m->status == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} uppercase">
                                    {{ $m->status }}
                                </span>
                            </td>
                            
                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('mitra.mobil.edit', $m->id) }}" class="bg-amber-400 hover:bg-amber-500 text-white p-2 rounded-lg shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('mitra.mobil.destroy', $m->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus mobil ini?')" class="bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-lg shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 font-bold uppercase italic">Belum ada armada mobil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
