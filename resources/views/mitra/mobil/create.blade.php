<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Armada Baru</h2>

                    {{-- ALARM ERROR VALIDASI (Penting agar Mitra tahu jika salah input) --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md shadow-sm">
                            <h3 class="font-bold text-red-800 mb-2">Gagal Menyimpan Data:</h3>
                            <ul class="text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Upload --}}
                    <form action="{{ route('mitra.mobil.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Cabang</label>
                                <select name="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->nama_cabang }} - {{ $branch->kota }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                                <input type="text" name="merk" placeholder="Contoh: Toyota" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                                <input type="text" name="model" placeholder="Contoh: Avanza Veloz" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            {{-- TAMBAHAN MUTLAK: Tipe Mobil (Wajib untuk Filter) --}}
                            <div class="col-span-2 md:col-span-1 border-l-4 border-blue-500 pl-4 bg-blue-50 py-2 rounded-r-md">
                                <label class="block text-gray-800 text-sm font-bold mb-2">Kategori Tipe Mobil <span class="text-red-500">*</span></label>
                                <select name="tipe_mobil" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="City Car">City Car</option>
                                    <option value="Compact MPV">Compact MPV</option>
                                    <option value="Luxury Sedan">Luxury Sedan</option>
                                    <option value="Mini MPV">Mini MPV</option>
                                    <option value="Minibus">Minibus</option>
                                    <option value="Minivan">Minivan</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Sedan">Sedan</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Wajib diisi agar masuk dalam filter pencarian.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Pembuatan</label>
                                <input type="number" name="tahun_buat" value="2023" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            {{-- UPDATE MUTLAK: Value Transmisi disamakan dengan database --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Transmisi <span class="text-red-500">*</span></label>
                                <select name="transmisi" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Transmisi --</option>
                                    <option value="matic">Automatic (Matic)</option>
                                    <option value="manual">Manual (MT)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Bahan Bakar</label>
                                <select name="bahan_bakar" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Bensin">Bensin</option>
                                    <option value="Solar">Solar</option>
                                    <option value="Listrik">Listrik</option>
                                </select>
                            </div>

                            {{-- UPDATE MUTLAK: Kapasitas Penumpang --}}
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kursi <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_kursi" min="2" max="20" placeholder="Contoh: 4, 7" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <p class="text-xs text-gray-500 mt-1">Hanya angka (Misal: 4, 7)</p>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa (Per Hari)</label>
                                <input type="number" name="harga_sewa" placeholder="350000" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-2 border border-gray-200 p-4 rounded-md bg-gray-50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Foto Mobil</label>
                                <input type="file" name="gambar" class="w-full bg-white border border-gray-300 rounded-md p-2" required>
                                <p class="text-xs text-gray-500 mt-2"><i class="fa-solid fa-circle-info"></i> Format yang diizinkan: JPG, PNG. Ukuran maksimal file adalah 2MB.</p>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('mitra.mobil.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded hover:bg-gray-300 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow-lg transition">
                                Simpan Mobil
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
