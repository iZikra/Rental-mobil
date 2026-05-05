<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Kendaraan: {{ $car->nama_mobil }}</h2>
        <a href="{{ route('cars.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
        @if ($errors->any())
            {{-- ... (kode error validasi) ... --}}
        @endif

        <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="nama_mobil" class="block text-sm font-medium text-gray-700">Nama Mobil</label>
                    <input type="text" name="nama_mobil" id="nama_mobil" value="{{ old('nama_mobil', $car->nama_mobil) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="merek" class="block text-sm font-medium text-gray-700">Merek Mobil</label>
                    <input type="text" name="merek" id="merek" value="{{ old('merek', $car->merek) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $car->type) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="jumlah_kursi" class="block text-sm font-medium text-gray-700">Jumlah Kursi</label>
                    <input type="number" name="jumlah_kursi" id="jumlah_kursi" value="{{ old('jumlah_kursi', $car->jumlah_kursi) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="transmisi" class="block text-sm font-medium text-gray-700">Transmisi</label>
                    <select name="transmisi" id="transmisi" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="Manual" {{ old('transmisi', $car->transmisi) == 'Manual' ? 'selected' : '' }}>Manual</option>
                        <option value="Otomatis" {{ old('transmisi', $car->transmisi) == 'Otomatis' ? 'selected' : '' }}>Otomatis</option>
                    </select>
                </div>
                <div>
                    <label for="bahan_bakar" class="block text-sm font-medium text-gray-700">Bahan Bakar</label>
                    <input type="text" name="bahan_bakar" id="bahan_bakar" value="{{ old('bahan_bakar', $car->bahan_bakar) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                 <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="tersedia" {{ old('status', $car->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ old('status', $car->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $car->deskripsi) }}</textarea>
                </div>
                 <div class="md:col-span-3">
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Ganti Gambar Mobil (Opsional)</label>
                    <img src="{{ Storage::url($car->gambar) }}" alt="{{ $car->nama_mobil }}" class="h-32 w-auto rounded-md my-2">
                    <input type="file" name="gambar" id="gambar" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>
            </div>

            {{-- Harga Mobil Saja --}}
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Harga Mobil Saja (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <label for="harga_mobil_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_mobil_12h" value="{{ old('harga_mobil_12h', $car->harga_mobil_12h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_mobil_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_mobil_24h" value="{{ old('harga_mobil_24h', $car->harga_mobil_24h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
            
            {{-- Harga Mobil + Driver --}}
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Harga Mobil + Driver (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_driver_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_driver_12h" value="{{ old('harga_driver_12h', $car->harga_driver_12h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_driver_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_driver_24h" value="{{ old('harga_driver_24h', $car->harga_driver_24h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- Harga Mobil + Sopir + Bensin --}}
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Mobil + Sopir + Bensin (Rp)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_bbm_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_bbm_12h" value="{{ old('harga_bbm_12h', $car->harga_bbm_12h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_bbm_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_bbm_24h" value="{{ old('harga_bbm_24h', $car->harga_bbm_24h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- Harga All Inclusive --}}
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Mobil + Sopir + Bensin + Parkir(tidak termasuk tol)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="harga_allin_12h" class="block text-sm font-medium text-gray-700">12 Jam</label>
                    <input type="number" name="harga_allin_12h" value="{{ old('harga_allin_12h', $car->harga_allin_12h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="harga_allin_24h" class="block text-sm font-medium text-gray-700">24 Jam / Hari</label>
                    <input type="number" name="harga_allin_24h" value="{{ old('harga_allin_24h', $car->harga_allin_24h) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- Fitur Tambahan (AC, P3K, dll.) --}}
            <h3 class="text-lg font-semibold border-b pb-2 mb-4 mt-8">Fitur Tambahan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                 <div>
                    <label for="p3k" class="block text-sm font-medium text-gray-700">P3K</label>
                    <select name="p3k" id="p3k" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya" {{ old('p3k', $car->p3k) == 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ old('p3k', $car->p3k) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="ac" class="block text-sm font-medium text-gray-700">AC</label>
                    <select name="ac" id="ac" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya" {{ old('ac', $car->ac) == 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ old('ac', $car->ac) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="audio" class="block text-sm font-medium text-gray-700">Audio</label>
                    <select name="audio" id="audio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                         <option value="ya" {{ old('audio', $car->audio) == 'ya' ? 'selected' : '' }}>Ya</option>
                         <option value="tidak" {{ old('audio', $car->audio) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                 <div>
                    <label for="charger" class="block text-sm font-medium text-gray-700">Charger</label>
                    <select name="charger" id="charger" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="ya" {{ old('charger', $car->charger) == 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ old('charger', $car->charger) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 text-right">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>