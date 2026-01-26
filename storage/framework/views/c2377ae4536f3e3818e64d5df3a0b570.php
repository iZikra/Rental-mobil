<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
            
            
            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-xl shadow-lg flex items-start gap-4 animate-bounce">
                    <div class="flex-shrink-0 text-red-500">
                        <i class="fa-solid fa-circle-exclamation text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800">Mohon Perbaiki Kesalahan Berikut:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('transaksi.store')); ?>" method="POST" enctype="multipart/form-data" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <?php echo csrf_field(); ?>
                
                
                <input type="hidden" name="total_harga" id="input_total_harga" value="<?php echo e(old('total_harga')); ?>">
                <input type="hidden" name="lama_sewa" id="input_lama_sewa" value="<?php echo e(old('lama_sewa')); ?>">
                <input type="hidden" name="status" value="Pending">

                
                <div class="lg:col-span-2 space-y-6">

                    
                    <?php if(!isset($selectedMobil) || !$selectedMobil): ?>
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">1</div>
                            <h2 class="text-xl font-bold text-gray-800">Pilih Armada</h2>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobil yang Ingin Disewa</label>
                            <select name="mobil_id" id="mobil_select" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-4 font-bold transition">
                                <option value="" data-harga="0">-- Pilih Mobil --</option>
                                <?php $__currentLoopData = $semuaMobil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>" data-harga="<?php echo e($m->harga_sewa); ?>" <?php echo e(old('mobil_id') == $m->id ? 'selected' : ''); ?>>
                                        <?php echo e($m->merek); ?> <?php echo e($m->model); ?> - Rp <?php echo e(number_format($m->harga_sewa)); ?>/hari
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <?php else: ?>
                        
                        <input type="hidden" name="mobil_id" value="<?php echo e($selectedMobil->id); ?>">
                    <?php endif; ?>

                    
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                <?php echo e((!isset($selectedMobil) || !$selectedMobil) ? '2' : '1'); ?>

                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Data Penyewa</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                                <div class="flex items-center bg-gray-100 border border-gray-200 rounded-xl px-4 py-3">
                                    <i class="fa-regular fa-user text-gray-400 mr-3"></i>
                                    <input type="text" value="<?php echo e(Auth::user()->name); ?>" readonly class="bg-transparent border-none w-full text-gray-500 font-semibold focus:ring-0 cursor-not-allowed">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">No. WhatsApp Aktif <span class="text-red-500">*</span></label>
                                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                    <i class="fa-brands fa-whatsapp text-green-500 mr-3 text-lg"></i>
                                    <input type="number" name="no_hp" value="<?php echo e(old('no_hp', Auth::user()->no_hp ?? '')); ?>" class="bg-transparent border-none w-full text-gray-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="0812xxxx" required>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Domisili <span class="text-red-500">*</span></label>
                                <div class="flex items-start bg-white border border-gray-300 rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition">
                                    <i class="fa-solid fa-map-pin text-red-500 mr-3 mt-1"></i>
                                    <textarea name="alamat" rows="2" class="bg-transparent border-none w-full text-gray-800 font-semibold focus:ring-0 placeholder-gray-400" placeholder="Alamat lengkap sesuai KTP..." required><?php echo e(old('alamat', Auth::user()->alamat ?? '')); ?></textarea>
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

                    
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                <?php echo e((!isset($selectedMobil) || !$selectedMobil) ? '3' : '2'); ?>

                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Detail Perjalanan</h2>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Mulai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_ambil" id="tgl_ambil" value="<?php echo e(old('tgl_ambil')); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="time" name="jam_ambil" id="jam_ambil" value="<?php echo e(old('jam_ambil')); ?>" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Selesai Sewa</label>
                                    <div class="flex gap-2">
                                        <input type="date" name="tgl_kembali" id="tgl_kembali" value="<?php echo e(old('tgl_kembali')); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                        <input type="time" name="jam_kembali" id="jam_kembali" value="<?php echo e(old('jam_kembali')); ?>" class="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-2 py-3 font-bold focus:ring-blue-500 text-gray-700" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Layanan Pengemudi</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="sopir" value="tanpa_sopir" class="peer sr-only" <?php echo e(old('sopir', 'tanpa_sopir') == 'tanpa_sopir' ? 'checked' : ''); ?>>
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
                                        <input type="radio" name="sopir" value="dengan_sopir" class="peer sr-only" <?php echo e(old('sopir') == 'dengan_sopir' ? 'checked' : ''); ?>>
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
                                <input type="text" name="tujuan" value="<?php echo e(old('tujuan')); ?>" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-700" placeholder="Contoh: Liburan ke Berastagi" required>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Penjemputan</label>
                                    <div class="flex gap-4 mb-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_ambil" value="kantor" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(false)" <?php echo e(old('lokasi_ambil', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Ambil di Kantor</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_ambil" value="lainnya" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(true)" <?php echo e(old('lokasi_ambil') == 'lainnya' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Diantar (+Biaya)</span>
                                        </label>
                                    </div>
                                    <input type="text" id="input_alamat_lain" name="alamat_lengkap" value="<?php echo e(old('alamat_lengkap')); ?>" class="<?php echo e(old('lokasi_ambil') == 'lainnya' ? '' : 'hidden'); ?> w-full border-blue-300 rounded-lg text-sm focus:ring-blue-500 mt-2" placeholder="Masukkan alamat lengkap penjemputan...">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lokasi Pengembalian</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_kembali" value="kantor" class="text-blue-600 focus:ring-blue-500" <?php echo e(old('lokasi_kembali', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Kembalikan ke Kantor</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="lokasi_kembali" value="lainnya" class="text-blue-600 focus:ring-blue-500" <?php echo e(old('lokasi_kembali') == 'lainnya' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm font-semibold text-gray-700">Jemput di Lokasi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-1">
                    <div class="sticky top-28 space-y-6">
                        
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-slate-900 px-6 py-4">
                                <h3 class="text-white font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-receipt"></i> Ringkasan Pesanan
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                
                                <?php if(isset($selectedMobil) && $selectedMobil): ?>
                                    <div class="text-center mb-6">
                                        <img src="<?php echo e(asset('storage/' . $selectedMobil->gambar)); ?>" class="w-full h-32 object-contain mb-4 transform hover:scale-105 transition duration-500">
                                        <h4 class="text-xl font-extrabold text-slate-800"><?php echo e($selectedMobil->merek); ?> <?php echo e($selectedMobil->model); ?></h4>
                                        <p class="text-sm text-gray-500 font-medium"><?php echo e($selectedMobil->tahun); ?> • <?php echo e($selectedMobil->transmisi); ?></p>
                                    </div>
                                
                                <?php else: ?>
                                    <div class="text-center mb-6" id="mobil_placeholder">
                                        <div class="w-full h-32 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-car text-4xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Silakan pilih mobil di form sebelah kiri.</p>
                                    </div>
                                <?php endif; ?>

                                <div class="space-y-3 border-t border-dashed border-gray-200 pt-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Harga Unit</span>
                                        <span class="font-bold text-gray-800" id="harga_unit_display">
                                            Rp <?php echo e(isset($selectedMobil) ? number_format($selectedMobil->harga_sewa) : '0'); ?>

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

                                <button type="submit" 
                                        onclick="this.disabled=true; this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\'></i> Memproses...'; document.getElementById('bookingForm').submit();" 
                                        class="w-full mt-6 bg-slate-900 hover:bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 flex justify-center items-center gap-2 group">
                                    Konfirmasi Booking
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                                
                                <p class="text-xs text-center text-gray-400 mt-4">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Data Anda diamankan dengan enkripsi.
                                </p>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                            <h4 class="font-bold text-blue-800 mb-2">Butuh Bantuan?</h4>
                            <p class="text-sm text-blue-600 mb-4">Hubungi admin jika Anda mengalami kesulitan saat pemesanan.</p>
                            <a href="https://wa.me/6285375285567" target="_blank" class="flex items-center justify-center gap-2 bg-white text-blue-600 font-bold py-2 rounded-lg border border-blue-200 hover:bg-blue-600 hover:text-white transition">
                                <i class="fa-brands fa-whatsapp"></i> Chat Admin
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
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

        // Sinkronisasi Jam Ambil -> Jam Kembali (UX Helper)
        const jamAmbil = document.getElementById('jam_ambil');
        const jamKembali = document.getElementById('jam_kembali');
        if(jamAmbil && jamKembali) {
            jamAmbil.addEventListener('change', function() {
                if(!jamKembali.value) jamKembali.value = this.value;
            });
        }

        // --- CORE CALCULATION LOGIC ---
        const tglAmbil = document.getElementById('tgl_ambil');
        const tglKembali = document.getElementById('tgl_kembali');
        const mobilSelect = document.getElementById('mobil_select');
        
        // Harga dasar dari PHP (Fallback jika mobil sudah dipilih)
        let hargaDasar = <?php echo e(isset($selectedMobil) ? $selectedMobil->harga_sewa : 0); ?>;
        const hargaSopirPerHari = 150000;

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function hitung() {
            // 1. Cek Harga Mobil (Dari Dropdown atau Variable PHP)
            if(mobilSelect) {
                const selectedOption = mobilSelect.options[mobilSelect.selectedIndex];
                if(selectedOption.value) {
                    hargaDasar = parseInt(selectedOption.getAttribute('data-harga'));
                    // Update Tampilan Harga Unit
                    document.getElementById('harga_unit_display').innerText = 'Rp ' + formatRupiah(hargaDasar);
                }
            }

            // 2. Hitung Durasi
            let totalDays = 0;
            if(tglAmbil.value && tglKembali.value) {
                const start = new Date(tglAmbil.value);
                const end = new Date(tglKembali.value);
                
                // Validasi Tanggal
                if (end < start) {
                    // Jangan alert terus menerus, cukup reset total
                    totalDays = 0;
                } else {
                    const diffTime = end - start;
                    const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    totalDays = days > 0 ? days : 1; // Minimal 1 hari
                }
            }

            // 3. Cek Opsi Sopir
            const sopirElem = document.querySelector('input[name="sopir"]:checked');
            const pakaiSopir = sopirElem ? sopirElem.value === 'dengan_sopir' : false;
            const totalSopir = pakaiSopir ? (hargaSopirPerHari * totalDays) : 0;

            // 4. Grand Total
            const grandTotal = (hargaDasar * totalDays) + totalSopir;

            // 5. Update UI
            document.getElementById('durasi_text').innerText = totalDays + ' Hari';
            document.getElementById('total_text').innerText = 'Rp ' + formatRupiah(grandTotal);
            
            if(pakaiSopir && totalDays > 0) {
                document.getElementById('row_sopir').classList.remove('hidden');
                document.getElementById('biaya_sopir_display').innerText = 'Rp ' + formatRupiah(totalSopir);
            } else {
                document.getElementById('row_sopir').classList.add('hidden');
            }

            // 6. Update Input Hidden (Wajib dikirim ke Controller)
            document.getElementById('input_total_harga').value = grandTotal;
            document.getElementById('input_lama_sewa').value = totalDays;
        }

        // Event Listeners
        if(tglAmbil && tglKembali) {
            tglAmbil.addEventListener('change', hitung);
            tglKembali.addEventListener('change', hitung);
        }
        
        // Listener untuk Radio Sopir
        document.querySelectorAll('input[name="sopir"]').forEach(el => {
            el.addEventListener('change', hitung);
        });

        // Listener untuk Dropdown Mobil (Jika ada)
        if(mobilSelect) {
            mobilSelect.addEventListener('change', hitung);
        }
        
        // Jalankan hitung saat load (untuk antisipasi old input)
        window.addEventListener('load', hitung);
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/pages/order.blade.php ENDPATH**/ ?>