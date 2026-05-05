<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Rental & Lokasi</h2>
            <p class="mt-2 text-gray-500">Kelola profil, alamat operasional, dan informasi pembayaran rental Anda dalam satu tempat.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Pengaturan Utama --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('mitra.pengaturan.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-6 sm:p-8 space-y-8">
                            
                            {{-- PROFIL & ALAMAT --}}
                            <div>
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-bold text-slate-800"><i class="fa-solid fa-store text-blue-600 mr-2"></i> Profil & Alamat Utama</h3>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100">Data Pusat</span>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Rental <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_rental" value="{{ old('nama_rental', $rental->nama_rental) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                        <textarea name="alamat" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: Jl. Sudirman No 12, Kec. Tampan, Kota Pekanbaru" required>{{ old('alamat', $rental->alamat) }}</textarea>
                                        <p class="text-xs text-gray-400 mt-2 italic"><b>Catatan:</b> Alamat ini akan digunakan sebagai lokasi penjemputan utama pada halaman pemesanan.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-tags text-indigo-600 mr-2"></i> Biaya Layanan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Sopir / Hari</label>
                                        <input type="number" name="biaya_sopir_per_hari" value="{{ old('biaya_sopir_per_hari', $rental->biaya_sopir_per_hari ?? 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="150000" min="0">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Bandara / Trip</label>
                                        <input type="number" name="biaya_bandara_per_trip" value="{{ old('biaya_bandara_per_trip', $rental->biaya_bandara_per_trip ?? 0) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="100000" min="0">
                                    </div>
                                </div>
                            </div>

                            {{-- INFORMASI REKENING BANK (WA INVOICE) --}}
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-building-columns text-emerald-600 mr-2"></i> Rekening Pembayaran (WA Invoice)</h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Bank</label>
                                            <input type="text" name="nama_bank" value="{{ old('nama_bank', $rental->nama_bank) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: BCA, Mandiri, BNI">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Rekening</label>
                                            <input type="text" name="no_rekening" value="{{ old('no_rekening', $rental->no_rekening) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="Contoh: 1234567890">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Atas Nama Rekening</label>
                                        <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $rental->atas_nama_rekening) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: PT Rental Mobil Berkah">
                                        <p class="text-[10px] text-emerald-600 mt-2 font-bold italic">* Data ini akan dikirimkan secara otomatis via WhatsApp sebagai Invoice Tagihan saat Anda menekan tombol "Terima & Tagih".</p>
                                    </div>
                                </div>
                            </div>

                            {{-- SYARAT DAN KETENTUAN --}}
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-file-contract text-orange-500 mr-2"></i> Syarat & Ketentuan Sewa</h3>
                                <div>
                                    <textarea name="syarat_ketentuan" rows="6" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-medium text-sm focus:ring-blue-500" placeholder="1. Penyewa wajib menyerahkan KTP asli...">{{ old('syarat_ketentuan', $rental->syarat_ketentuan) }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition">
                                Simpan Semua Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar Informasi Lokasi (Cabang) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center justify-between">
                        Daftar Lokasi (Cabang)
                        <a href="{{ route('mitra.cabang.index') }}" class="text-[10px] bg-slate-100 hover:bg-slate-200 px-2 py-1 rounded text-slate-500 transition">Kunjungi Menu Cabang</a>
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($branches as $branch)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 relative group">
                                <h4 class="font-bold text-slate-800 text-sm mb-1">{{ $branch->nama_cabang }}</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $branch->alamat_lengkap }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-[9px] font-bold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded uppercase">{{ $branch->kota }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 italic">ID: #{{ $branch->id }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400 italic">Belum ada cabang terdaftar.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($branches->count() <= 1)
                        <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                <i class="fa-solid fa-circle-info mr-1"></i> 
                                Karena Anda hanya memiliki 1 lokasi, sistem akan otomatis menyamakan alamat Cabang dengan Alamat Rental saat Anda menekan tombol simpan.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="bg-slate-900 rounded-2xl p-6 text-white overflow-hidden relative shadow-xl">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl"></div>
                    <h4 class="font-bold text-sm mb-2 relative z-10">Penting!</h4>
                    <p class="text-xs text-slate-400 leading-relaxed relative z-10">
                        Pastikan Alamat Lengkap sudah benar. Alamat ini digunakan oleh AI Bot dan Halaman Booking untuk membantu pelanggan menemukan lokasi Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
