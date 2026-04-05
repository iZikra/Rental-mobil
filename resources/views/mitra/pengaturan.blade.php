<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Rental</h2>
            <p class="mt-2 text-gray-500">Kelola profil, alamat operasional, dan informasi pembayaran rental Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        {{-- ALARM ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
                <h3 class="font-bold text-red-800 flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal Menyimpan!
                </h3>
                <ul class="text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('mitra.pengaturan.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8 space-y-8">
                    
                    {{-- PROFIL & ALAMAT --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-store text-blue-600 mr-2"></i> Profil & Lokasi</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Rental <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_rental" value="{{ old('nama_rental', $rental->nama_rental) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap (Untuk Google Maps) <span class="text-red-500">*</span></label>
                                <textarea name="alamat" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: Jl. Sudirman No 12, Kec. Tampan, Kota Pekanbaru" required>{{ old('alamat', $rental->alamat) }}</textarea>
                                <p class="text-xs text-gray-400 mt-1">Pastikan alamat akurat agar titik Google Maps di halaman pemesanan sesuai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI PEMBAYARAN --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-money-check-dollar text-green-600 mr-2"></i> Rekening Pencairan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Bank</label>
                                <input type="text" name="nama_bank" value="{{ old('nama_bank', $rental->nama_bank) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: BCA / MANDIRI">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Rekening</label>
                                <input type="number" name="no_rekening" value="{{ old('no_rekening', $rental->no_rekening) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="1234567890">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Atas Nama</label>
                                <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $rental->atas_nama_rekening) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Nama Pemilik Rekening">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-tags text-indigo-600 mr-2"></i> Biaya Layanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Sopir / Hari</label>
                                <input type="number" name="biaya_sopir_per_hari" value="{{ old('biaya_sopir_per_hari', $rental->biaya_sopir_per_hari ?? 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="150000" min="0">
                                <p class="text-xs text-gray-400 mt-1">Dipakai saat pelanggan memilih opsi Dengan Sopir.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Bandara / Trip</label>
                                <input type="number" name="biaya_bandara_per_trip" value="{{ old('biaya_bandara_per_trip', $rental->biaya_bandara_per_trip ?? 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="100000" min="0">
                                <p class="text-xs text-gray-400 mt-1">Dipakai untuk Jemput di Bandara dan Antar ke Bandara.</p>
                            </div>
                        </div>
                    </div>

                    {{-- SYARAT DAN KETENTUAN --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-file-contract text-orange-500 mr-2"></i> Syarat & Ketentuan Sewa</h3>
                        <div>
                            <textarea name="syarat_ketentuan" rows="8" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-medium text-sm focus:ring-blue-500" placeholder="1. Penyewa wajib menyerahkan KTP asli...&#10;2. Dilarang merokok di dalam mobil...">{{ old('syarat_ketentuan', $rental->syarat_ketentuan) }}</textarea>
                            <p class="text-xs text-gray-400 mt-2">Aturan ini akan diwajibkan untuk disetujui (dicentang) oleh pelanggan saat melakukan booking.</p>
                        </div>
                    </div>

                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
