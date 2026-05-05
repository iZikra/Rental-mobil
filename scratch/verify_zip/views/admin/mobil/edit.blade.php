<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Mobil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('mobils.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                            <input type="text" name="merk" value="{{ old('merk', $mobil->merk) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                            <input type="text" name="model" value="{{ old('model', $mobil->model) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Plat</label>
                            <input type="text" name="no_plat" value="{{ old('no_plat', $mobil->no_plat) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa per Hari (Rp)</label>
                            <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $mobil->harga_sewa) }}" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto Mobil (Opsional)</label>
                            
                            @if($mobil->gambar)
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500">Foto Saat Ini:</span>
                                    <img src="{{ asset('img/' . $mobil->gambar) }}" alt="Foto Lama" class="h-20 w-auto rounded mt-1 border border-gray-300">
                                </div>
                            @endif
                            
                            <input type="file" name="gambar" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100">
                            <p class="text-xs text-gray-500 mt-1">*Biarkan kosong jika tidak ingin mengganti foto.</p>
                        </div>

                        <div class="flex items-center justify-between mt-6">
        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                            💾 Simpan Perubahan
                        </button>

                        <a href="{{ route('mobils.index') }}" class="text-gray-500 hover:text-red-500 font-bold text-sm transition">
                            ✕ Batal
                        </a>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>