<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="relative bg-slate-900 py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30" alt="Booking Header">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 to-slate-900/90"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">Selesaikan Reservasi Anda</h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto">Lengkapi formulir di bawah ini untuk mengamankan kendaraan pilihan Anda.</p>
        </div>
    </div>

    <div class="relative -mt-10 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- ERROR HANDLING --}}
            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg flex items-start gap-4 animate-bounce">
                    <div class="flex-shrink-0 text-red-500">
                        <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Mohon Perbaiki Kesalahan Berikut:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                {{-- DATA HIDDEN --}}
                <input type="hidden" name="total_harga" id="input_total_harga" value="{{ old('total_harga') }}">
                <input type="hidden" name="lama_sewa" id="input_lama_sewa" value="{{ old('lama_sewa') }}">
                <input type="hidden" name="status" value="Pending">

                {{-- KOLOM KIRI (FORM INPUT) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- CARD 1: INFORMASI KENDARAAN --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">1</div>
                            <h2 class="text-xl font-bold text-gray-800">Pilih Armada</h2>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobil yang Ingin Disewa</label>
                            <select name="mobil_id" id="mobil_select" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-4 font-bold transition">
                                <option value="" data-harga="0" data-img="" data-nama="">-- Pilih Mobil --</option>
                                @foreach($semuaMobil as $m)
                                    @php
                                        $imgUrl = Str::startsWith($m->gambar, 'cars/') ? asset('storage/' . $m->gambar) : asset('img/' . $m->gambar);
                                    @endphp
                                    
                                    <option value="{{ $m->id }}" 
                                            data-harga="{{ $m->harga_sewa }}" 
                                            data-img="{{ $imgUrl }}"
                                            data-nama="{{ $m->merek }} {{ $m->model }}"
                                            data-desc="{{ $m->tahun }} • {{ $m->transmisi }}"
                                            {{ (isset($selectedMobil) && $selectedMobil->id == $m->id) || old('mobil_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->merek }} {{ $m->model }} - Rp {{ number_format($m->harga_sewa) }}/hari
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- CARD 2: DATA DIRI --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">2</div>
                            <h2 class="text-xl font-bold text-gray-800">Data Penyewa</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                                <div class="flex items-center bg-gray-100 border border-gray-200 rounded-xl px-4 py-3">
                                    <i class="fa-regular fa-user text-gray-400 mr-3"></i>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly class="bg-transparent border-none w-full text-gray-500 font-semibold focus:ring-0 cursor-not-allowed">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">No. WhatsApp Aktif <span class="text-red-500">*</span></label>
                                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                    <i class="fa-brands fa-whatsapp text-green-500 mr-3 text-lg"></i>
                                    <input type="number" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp ?? '') }}" class="bg-transparent border-none w-full text-gray-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="0812xxxx" required>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Domisili <span class="text-red-500">*</span></label>
                                <div class="flex items-start bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                    <i class="fa-solid fa-map-pin text-red-500 mr-3 mt-1"></i>
                                    <textarea name="alamat" rows="2" class="bg-transparent border-none w-full text-gray-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="Alamat lengkap sesuai KTP..." required>{{ old('alamat', Auth::user()->alamat ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Foto KTP / SIM (Identitas Asli) <span class="text-red-500">*</span></label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('file_ktp').click()">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-medium">Klik untuk upload foto KTP</p>
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    <input type="file" name="foto_identitas" id="file_ktp" class="hidden" onchange="previewFile()">
                                </div>
                                <p id="file_name" class="text-center text-sm text-blue-600 font-bold mt-2 hidden"></p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: DETAIL SEWA --}}
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">3</div>
                            <h2 class="text-xl font-bold text-gray-800">Detail Perjalanan</h2>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Mulai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_ambil" id="tgl_ambil" min="{{ date('Y-m-d') }}" value="{{ old('tgl_ambil', date('Y-m-d')) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="time" name="jam_ambil" id="jam_ambil" value="{{ old('jam_ambil') }}" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Selesai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_kembali" id="tgl_kembali" min="{{ date('Y-m-d') }}" value="{{ old('tgl_kembali') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="time" name="jam_kembali" id="jam_kembali" value="{{ old('jam_kembali') }}" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Layanan Pengemudi</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="sopir" value="tanpa_sopir" class="peer sr-only" {{ old('sopir', 'tanpa_sopir') == 'tanpa_sopir' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition hover:bg-gray-50 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                                    <i class="fa-solid fa-key"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800">Lepas Kunci</h4>
                                                    <p class="text-xs text-gray-500">Setir sendiri</p>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-circle-check text-blue-500 text-xl opacity-0 peer-checked:opacity-100 transition"></i>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="sopir" value="dengan_sopir" class="peer sr-only" {{ old('sopir') == 'dengan_sopir' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition hover:bg-gray-50 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                                    <i class="fa-solid fa-user-tie"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800">Dengan Sopir</h4>
                                                    <p class="text-xs text-gray-500">+Rp 150rb/hari</p>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-circle-check text-blue-500 text-xl opacity-0 peer-checked:opacity-100 transition"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tujuan Penggunaan</label>
                                <input type="text" name="tujuan" value="{{ old('tujuan') }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-700" placeholder="Contoh: Liburan ke Berastagi" required>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Penjemputan</label>
                                    <div class="flex gap-4 mb-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_ambil" value="kantor" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(false)" {{ old('lokasi_ambil', 'kantor') == 'kantor' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Ambil di Kantor</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_ambil" value="lainnya" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(true)" {{ old('lokasi_ambil') == 'lainnya' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Diantar (+Biaya)</span>
                                        </label>
                                    </div>
                                    <input type="text" id="input_alamat_lain" name="alamat_lengkap" value="{{ old('alamat_lengkap') }}" class="{{ old('lokasi_ambil') == 'lainnya' ? '' : 'hidden' }} w-full border-blue-300 rounded-lg text-sm focus:ring-blue-500 mt-2" placeholder="Masukkan alamat lengkap penjemputan...">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Pengembalian</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_kembali" value="kantor" class="text-blue-600 focus:ring-blue-500" {{ old('lokasi_kembali', 'kantor') == 'kantor' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Kembalikan ke Kantor</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_kembali" value="lainnya" class="text-blue-600 focus:ring-blue-500" {{ old('lokasi_kembali') == 'lainnya' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Jemput di Lokasi (+Biaya)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (RINGKASAN STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-28 space-y-6">
                        
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-slate-900 px-6 py-4">
                                <h3 class="text-white font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-receipt"></i> Ringkasan Pesanan
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                {{-- CONTAINER DINAMIS (Diatur oleh JS) --}}
                                <div id="summary_content" class="{{ isset($selectedMobil) ? '' : 'hidden' }}">
                                    <div class="text-center mb-6">
                                        <img id="summary_img" 
                                             src="{{ isset($selectedMobil) ? (Str::startsWith($selectedMobil->gambar, 'cars/') ? asset('storage/' . $selectedMobil->gambar) : asset('img/' . $selectedMobil->gambar)) : '' }}" 
                                             class="w-full h-32 object-contain mb-4 transform hover:scale-105 transition duration-500 rounded">
                                        <h4 id="summary_title" class="text-xl font-extrabold text-slate-800">
                                            {{ isset($selectedMobil) ? $selectedMobil->merek . ' ' . $selectedMobil->model : '' }}
                                        </h4>
                                        <p id="summary_desc" class="text-sm text-gray-500 font-medium">
                                            {{ isset($selectedMobil) ? $selectedMobil->tahun . ' • ' . $selectedMobil->transmisi : '' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- PLACEHOLDER (Muncul jika belum pilih mobil) --}}
                                <div id="mobil_placeholder" class="text-center mb-6 {{ isset($selectedMobil) ? 'hidden' : '' }}">
                                    <div class="w-full h-32 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-car text-4xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Silakan pilih mobil di form sebelah kiri.</p>
                                </div>

                                <div class="space-y-3 border-t border-dashed border-gray-200 pt-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Harga Unit</span>
                                        <span class="font-bold text-gray-800" id="harga_unit_display">
                                            Rp {{ isset($selectedMobil) ? number_format($selectedMobil->harga_sewa) : '0' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Durasi Sewa</span>
                                        <span class="font-bold text-blue-600" id="durasi_text">0 Hari</span>
                                    </div>
                                    <div class="flex justify-between text-sm hidden" id="row_sopir">
                                        <span class="text-gray-500">Biaya Sopir</span>
                                        <span class="font-bold text-gray-800" id="biaya_sopir_display">Rp 0</span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t-2 border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-bold">Total Estimasi</span>
                                        <span class="text-2xl font-extrabold text-blue-600" id="total_text">Rp 0</span>
                                    </div>
                                </div>

                                {{-- INFO REKENING PEMBAYARAN --}}
                                <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                                    <h4 class="text-sm font-bold text-blue-800 uppercase mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-university"></i> Informasi Pembayaran
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Bank</span>
                                            <span class="text-sm font-bold text-gray-800">BRI (Bank Rakyat Indonesia)</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">No. Rekening</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-mono font-bold text-blue-700" id="no_rek">1234567890</span>
                                                <button type="button" onclick="navigator.clipboard.writeText('1234567890')" class="text-xs text-blue-500 hover:text-blue-700">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Atas Nama</span>
                                            <span class="text-sm font-bold text-gray-800">Zikrallah Al Hady</span>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-[10px] text-blue-600 leading-tight italic">
                                        *Silakan transfer sesuai <strong>Total Estimasi</strong>. Konfirmasi booking akan diproses setelah bukti bayar diunggah di halaman riwayat.
                                    </p>
                                </div>

                                <button type="submit" 
                                        class="w-full mt-6 bg-slate-900 hover:bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 flex justify-center items-center gap-2 group">
                                    Konfirmasi Booking
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                                
                                <p class="text-xs text-center text-gray-400 mt-4">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Data Anda diamankan dengan enkripsi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIC JAVASCRIPT --}}
    <script>
        // File Upload Preview
        function previewFile() {
            const file = document.getElementById('file_ktp').files[0];
            const nameLabel = document.getElementById('file_name');
            if(file) {
                nameLabel.innerText = "File Terpilih: " + file.name;
                nameLabel.classList.remove('hidden');
            }
        }

        // Toggle Alamat Lain
        function toggleLokasi(show) {
            const input = document.getElementById('input_alamat_lain');
            if(show) {
                input.classList.remove('hidden');
                input.focus();
            } else {
                input.classList.add('hidden');
                input.value = ''; // Reset
            }
        }

        // --- NEW LOGIC: ANTI MESIN WAKTU ---
        function validateTimeRealtime() {
            const tglAmbil = document.getElementById('tgl_ambil');
            const jamAmbil = document.getElementById('jam_ambil');
            if(!tglAmbil || !jamAmbil) return;

            const now = new Date();
            const selectedDate = new Date(tglAmbil.value);
            
            // Format jam sekarang HH:MM
            const currentHour = now.getHours().toString().padStart(2, '0');
            const currentMinute = now.getMinutes().toString().padStart(2, '0');
            const currentTime = `${currentHour}:${currentMinute}`;

            // Jika memilih hari ini (Local Date comparison)
            if (selectedDate.toDateString() === now.toDateString()) {
                jamAmbil.setAttribute('min', currentTime);
                if (jamAmbil.value && jamAmbil.value < currentTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu Tidak Valid',
                        text: 'Maaf, waktu jemput tidak boleh kurang dari jam sekarang!',
                        confirmButtonColor: '#0f172a'
                    });
                    jamAmbil.value = currentTime;
                }
            } else {
                jamAmbil.removeAttribute('min');
            }
        }

        // Sinkronisasi Jam Ambil -> Jam Kembali & Validasi
        const jamAmbil = document.getElementById('jam_ambil');
        const jamKembali = document.getElementById('jam_kembali');
        if(jamAmbil && jamKembali) {
            jamAmbil.addEventListener('change', function() {
                validateTimeRealtime();
                if(!jamKembali.value) jamKembali.value = this.value;
            });
            jamAmbil.addEventListener('input', validateTimeRealtime);
        }

        // --- CORE LOGIC ---
        const tglAmbil = document.getElementById('tgl_ambil');
        const tglKembali = document.getElementById('tgl_kembali');
        const mobilSelect = document.getElementById('mobil_select');
        const summaryContent = document.getElementById('summary_content');
        const summaryPlaceholder = document.getElementById('mobil_placeholder');
        const summaryImg = document.getElementById('summary_img');
        const summaryTitle = document.getElementById('summary_title');
        const summaryDesc = document.getElementById('summary_desc');
        
        let hargaDasar = {{ isset($selectedMobil) ? $selectedMobil->harga_sewa : 0 }};
        const hargaSopirPerHari = 150000;

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function hitung() {
            validateTimeRealtime(); // Validasi setiap kali hitung dipanggil

            if(mobilSelect) {
                const selectedOption = mobilSelect.options[mobilSelect.selectedIndex];
                
                if(selectedOption.value) {
                    hargaDasar = parseInt(selectedOption.getAttribute('data-harga'));
                    summaryImg.src = selectedOption.getAttribute('data-img');
                    summaryTitle.innerText = selectedOption.getAttribute('data-nama');
                    summaryDesc.innerText = selectedOption.getAttribute('data-desc');
                    summaryContent.classList.remove('hidden');
                    summaryPlaceholder.classList.add('hidden');
                    document.getElementById('harga_unit_display').innerText = 'Rp ' + formatRupiah(hargaDasar);
                } else {
                    summaryContent.classList.add('hidden');
                    summaryPlaceholder.classList.remove('hidden');
                    hargaDasar = 0;
                    document.getElementById('harga_unit_display').innerText = 'Rp 0';
                }
            }

            let totalDays = 0;
            if(tglAmbil.value && tglKembali.value) {
                const start = new Date(tglAmbil.value);
                const end = new Date(tglKembali.value);
                
                if (end < start) {
                    totalDays = 0;
                } else {
                    const diffTime = end - start;
                    const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    totalDays = days > 0 ? days : 1; 
                }
            }

            const sopirElem = document.querySelector('input[name="sopir"]:checked');
            const pakaiSopir = sopirElem ? sopirElem.value === 'dengan_sopir' : false;
            const totalSopir = pakaiSopir ? (hargaSopirPerHari * totalDays) : 0;
            const grandTotal = (hargaDasar * totalDays) + totalSopir;

            document.getElementById('durasi_text').innerText = totalDays + ' Hari';
            document.getElementById('total_text').innerText = 'Rp ' + formatRupiah(grandTotal);
            
            if(pakaiSopir && totalDays > 0) {
                document.getElementById('row_sopir').classList.remove('hidden');
                document.getElementById('biaya_sopir_display').innerText = 'Rp ' + formatRupiah(totalSopir);
            } else {
                document.getElementById('row_sopir').classList.add('hidden');
            }

            document.getElementById('input_total_harga').value = grandTotal;
            document.getElementById('input_lama_sewa').value = totalDays;
        }

        if(tglAmbil && tglKembali) {
            tglAmbil.addEventListener('change', hitung);
            tglKembali.addEventListener('change', hitung);
        }
        
        document.querySelectorAll('input[name="sopir"]').forEach(el => {
            el.addEventListener('change', hitung);
        });

        if(mobilSelect) {
            mobilSelect.addEventListener('change', hitung);
        }

        // Form Submission Loader
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
        });
        
        window.addEventListener('load', hitung);
    </script>
</x-app-layout>