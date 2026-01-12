<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Armada Mobil</h1>
                    <p class="text-sm text-gray-500">Total {{ $mobils->count() }} unit kendaraan terdaftar.</p>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('mobils.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Mobil
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 font-bold">×</button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($mobils as $mobil)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col group">
                    
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        @if ($mobil->gambar)
                            <img src="{{ asset('img/' . $mobil->gambar) }}" alt="{{ $mobil->merk }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3">
                            @if($mobil->status == 'tersedia')
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-sm">
                                    ✓ Tersedia
                                </span>
                            @else
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg shadow-sm">
                                    ✕ Disewa
                                </span>
                            @endif
                        </div>
                        
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-black/60 backdrop-blur-sm text-white text-xs font-mono px-2 py-1 rounded">
                                {{ $mobil->no_polisi }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</h3>
                                <p class="text-sm text-gray-500">Tahun {{ $mobil->tahun }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-red-600 font-bold text-lg">Rp {{ number_format($mobil->harga_sewa) }}</p>
                                <p class="text-xs text-gray-400">/hari</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-4 pt-4 flex justify-between text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                {{ $mobil->bahan_bakar ?? 'Bensin' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                5 Kursi
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('mobils.edit', $mobil->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center gap-1 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Data
                        </a>
                        
                        <form action="{{ route('mobils.destroy', $mobil->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mobil ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>

            @if($mobils->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl border-dashed border-2 border-gray-300 mt-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada armada</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan mobil baru ke sistem.</p>
                <div class="mt-6">
                    <a href="{{ route('mobils.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Mobil Baru
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>