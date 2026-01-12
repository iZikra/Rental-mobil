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

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900">Formulir Pemesanan</h1>
                <p class="mt-2 text-gray-600">Lengkapi data perjalanan Anda</p>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-bold text-red-800">Gagal Melanjutkan!</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('transaksi.store')); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8" novalidate id="bookingForm">
                <?php echo csrf_field(); ?>
                
                
                <?php
                    $final_id = $selectedMobil->id ?? old('mobil_id') ?? old('mobil') ?? request('mobil_id');
                ?>
                <input type="hidden" name="mobil_id" value="<?php echo e($final_id); ?>">
                <input type="hidden" name="mobil" value="<?php echo e($final_id); ?>">

                
                <input type="hidden" name="total_harga" id="input_total_harga" value="<?php echo e(old('total_harga')); ?>">
                <input type="hidden" name="lama_sewa" id="input_lama_sewa" value="<?php echo e(old('lama_sewa')); ?>">
                <input type="hidden" name="status" value="Pending">

                
                <div class="lg:col-span-2 space-y-8">

                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                            Data Penyewa
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" value="<?php echo e(Auth::user()->name); ?>" readonly class="w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="no_hp" value="<?php echo e(old('no_hp', Auth::user()->no_hp ?? '')); ?>" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="0812..." required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <textarea name="alamat" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Nama Jalan, RT/RW, Kelurahan..." required><?php echo e(old('alamat', Auth::user()->alamat ?? '')); ?></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto KTP / SIM <span class="text-red-500">*</span></label>
                                <input type="file" name="foto_identitas" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-gray-300 rounded-lg cursor-pointer">
                                <?php $__errorArgs = ['foto_ktp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-sm mt-1 italic"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-red-600"></div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span class="bg-red-100 text-red-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                            Detail Perjalanan
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_ambil" id="tgl_ambil" value="<?php echo e(old('tgl_ambil')); ?>" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_ambil" id="jam_ambil" value="<?php echo e(old('jam_ambil')); ?>" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" name="tgl_kembali" id="tgl_kembali" value="<?php echo e(old('tgl_kembali')); ?>" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                                <input type="time" name="jam_kembali" id="jam_kembali" value="<?php echo e(old('jam_kembali')); ?>" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Penggunaan <span class="text-red-500">*</span></label>
                                <input type="text" name="tujuan" value="<?php echo e(old('tujuan')); ?>" placeholder="Contoh: Wisata Dalam Kota" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" required>
                            </div>
                        </div>

                        <hr class="border-gray-200 my-6">

                        
                        <div class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Layanan Driver</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="border p-4 rounded-xl cursor-pointer hover:bg-gray-50 flex justify-between items-center transition <?php echo e(old('sopir') == 'tanpa_sopir' ? 'ring-2 ring-blue-500 bg-blue-50' : ''); ?>">
                                        <div class="flex items-center">
                                            <input type="radio" name="sopir" value="tanpa_sopir" class="text-blue-600 focus:ring-blue-500" <?php echo e(old('sopir', 'tanpa_sopir') == 'tanpa_sopir' ? 'checked' : ''); ?>>
                                            <span class="ml-2 font-medium text-gray-700">Lepas Kunci</span>
                                        </div>
                                        <span class="text-xs font-bold text-blue-600 bg-white px-2 py-1 rounded shadow-sm">Hemat</span>
                                    </label>
                                    <label class="border p-4 rounded-xl cursor-pointer hover:bg-gray-50 flex justify-between items-center transition <?php echo e(old('sopir') == 'dengan_sopir' ? 'ring-2 ring-red-500 bg-red-50' : ''); ?>">
                                        <div class="flex items-center">
                                            <input type="radio" name="sopir" value="dengan_sopir" class="text-red-600 focus:ring-red-500" <?php echo e(old('sopir') == 'dengan_sopir' ? 'checked' : ''); ?>>
                                            <span class="ml-2 font-medium text-gray-700">Dengan Sopir</span>
                                        </div>
                                        <span class="text-xs font-bold text-gray-500">+Rp 150rb/hari</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Lokasi Penjemputan</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="lokasi_ambil" value="kantor" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(false)" <?php echo e(old('lokasi_ambil', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-gray-700">Ambil di Kantor (Gratis)</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="lokasi_ambil" value="lainnya" class="text-blue-600 focus:ring-blue-500" onclick="toggleLokasi(true)" <?php echo e(old('lokasi_ambil') == 'lainnya' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-gray-700">Diantar ke Lokasi (Biaya)</span>
                                        </label>
                                        <input type="text" id="input_alamat_lain" name="alamat_lengkap" 
                                            value="<?php echo e(old('alamat_lengkap')); ?>"
                                            class="<?php echo e(old('lokasi_ambil') == 'lainnya' ? '' : 'hidden'); ?> w-full mt-2 border-blue-300 rounded-lg text-sm focus:ring-blue-500" 
                                            placeholder="Alamat penjemputan...">
                                    </div>
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Lokasi Pengembalian</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="lokasi_kembali" value="kantor" class="text-blue-600 focus:ring-blue-500" <?php echo e(old('lokasi_kembali', 'kantor') == 'kantor' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-gray-700">Kembalikan ke Kantor</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="lokasi_kembali" value="lainnya" class="text-blue-600 focus:ring-blue-500" <?php echo e(old('lokasi_kembali') == 'lainnya' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-gray-700">Kembalikan di Lokasi Lain</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="bg-gray-900 rounded-t-xl p-4 text-center">
                            <h3 class="text-white font-bold tracking-widest uppercase text-sm">Ringkasan Pesanan</h3>
                        </div>
                        <div class="bg-white border border-gray-200 border-t-0 rounded-b-xl p-6 shadow-lg">
                            
                            
                            <?php if(isset($selectedMobil) && $selectedMobil): ?>
                                <div class="text-center mb-6">
                                    <h4 class="text-xl font-extrabold text-gray-800"><?php echo e($selectedMobil->merk); ?> <?php echo e($selectedMobil->model); ?></h4>
                                    <p class="text-sm text-gray-500"><?php echo e($selectedMobil->tahun); ?></p>
                                    
                                    <?php if($selectedMobil->gambar): ?>
                                        <div class="my-4 relative group">
                                            <img src="<?php echo e(asset('img/' . $selectedMobil->gambar)); ?>" class="w-full h-32 object-cover rounded-lg shadow-sm">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo e(route('dashboard')); ?>" class="text-xs text-red-500 underline hover:text-red-700">Ubah Mobil</a>
                                </div>
                            <?php else: ?>
                                <div class="bg-red-50 p-4 rounded-lg text-center mb-6 border border-red-200">
                                    <p class="text-red-700 font-bold text-sm">Mobil Tidak Terdeteksi!</p>
                                    <a href="<?php echo e(route('dashboard')); ?>" class="mt-2 inline-block px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">Pilih Ulang</a>
                                </div>
                            <?php endif; ?>

                            <div class="border-t border-dashed border-gray-200 my-4 pt-4 space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Harga Sewa</span>
                                    <span class="font-bold">Rp <?php echo e(number_format($selectedMobil->harga_sewa ?? 0)); ?> /hari</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Durasi</span>
                                    <span class="font-bold" id="durasi_text">- Hari</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Estimasi</span>
                                    <span class="font-bold text-xl text-blue-600" id="total_text">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" 
                                    onclick="this.disabled=true; this.innerHTML='⏳ Memproses...'; this.form.submit();" 
                                    class="w-full bg-red-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-red-700 transition transform hover:-translate-y-1 active:scale-95 mt-6">
                                Konfirmasi Pesanan →
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    
    <script>
        function toggleLokasi(show) {
            const input = document.getElementById('input_alamat_lain');
            if(show) {
                input.classList.remove('hidden');
                input.focus();
            } else {
                input.classList.add('hidden');
            }
        }

        const jamAmbil = document.getElementById('jam_ambil');
        const jamKembali = document.getElementById('jam_kembali');
        if(jamAmbil && jamKembali) {
            jamAmbil.addEventListener('change', function() {
                if(!jamKembali.value) jamKembali.value = this.value;
            });
        }

        const tglAmbil = document.getElementById('tgl_ambil');
        const tglKembali = document.getElementById('tgl_kembali');
        const hargaDasar = <?php echo e($selectedMobil->harga_sewa ?? 0); ?>;
        const hargaSopir = 150000;

        function hitung() {
            if(tglAmbil.value && tglKembali.value) {
                const start = new Date(tglAmbil.value);
                const end = new Date(tglKembali.value);
                const diffTime = end - start;
                const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                const totalDays = days > 0 ? days : 1;

                const sopirElem = document.querySelector('input[name="sopir"]:checked');
                const pakaiSopir = sopirElem ? sopirElem.value === 'dengan_sopir' : false;
                
                const totalSopir = pakaiSopir ? (hargaSopir * totalDays) : 0;
                const grandTotal = (hargaDasar * totalDays) + totalSopir;

                // UPDATE TAMPILAN
                document.getElementById('durasi_text').innerText = totalDays + ' Hari';
                document.getElementById('total_text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);

                // UPDATE INPUT HIDDEN (PENTING UNTUK KIRIM KE CONTROLLER)
                document.getElementById('input_total_harga').value = grandTotal;
                document.getElementById('input_lama_sewa').value = totalDays;
            }
        }

        if(tglAmbil && tglKembali) {
            tglAmbil.addEventListener('change', hitung);
            tglKembali.addEventListener('change', hitung);
        }
        document.querySelectorAll('input[name="sopir"]').forEach(el => {
            el.addEventListener('change', hitung);
        });
        
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