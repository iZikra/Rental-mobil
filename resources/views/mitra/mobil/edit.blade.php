<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Edit Mobil: {{ $mobil->merk }} {{ $mobil->model }}</h2>
                        <a href="{{ route('mitra.mobil.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">&larr; Kembali</a>
                    </div>

                    {{-- Form Update (Method PUT) --}}
                    <form action="{{ route('mitra.mobil.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Cabang</label>
                                <select name="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $mobil->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->nama_cabang }} - {{ $branch->kota }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                                <input type="text" name="merk" value="{{ old('merk', $mobil->merk) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                                <input type="text" name="model" value="{{ old('model', $mobil->model) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Plat</label>
                                <input type="text" name="no_plat" value="{{ old('no_plat', $mobil->no_plat) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Pembuatan</label>
                                <input type="number" name="tahun_buat" value="{{ old('tahun_buat', $mobil->tahun_buat) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Transmisi</label>
                                <select name="transmisi" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Manual" {{ $mobil->transmisi == 'Manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="Matic" {{ $mobil->transmisi == 'Matic' ? 'selected' : '' }}>Matic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Bahan Bakar</label>
                                <select name="bahan_bakar" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Bensin" {{ $mobil->bahan_bakar == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                                    <option value="Solar" {{ $mobil->bahan_bakar == 'Solar' ? 'selected' : '' }}>Solar</option>
                                    <option value="Listrik" {{ $mobil->bahan_bakar == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kursi</label>
                                <input type="number" name="jumlah_kursi" value="{{ old('jumlah_kursi', $mobil->jumlah_kursi) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa (Per Hari)</label>
                                <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $mobil->harga_sewa) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto (Opsional)</label>
                                <input type="file" name="gambar" class="w-full border border-gray-300 rounded-md p-2">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                                
                                @if($mobil->gambar)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-600 mb-1">Foto Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $mobil->gambar) }}" class="h-24 w-auto rounded border">
                                    </div>
                                @endif
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('mitra.mobil.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow-lg">
                                Update Data Mobil
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>