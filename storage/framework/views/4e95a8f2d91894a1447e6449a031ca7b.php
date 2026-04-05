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
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Rental</h2>
            <p class="mt-2 text-gray-500">Kelola profil, alamat operasional, dan informasi pembayaran rental Anda.</p>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-200 font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
                <h3 class="font-bold text-red-800 flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal Menyimpan!
                </h3>
                <ul class="text-sm text-red-700 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="<?php echo e(route('mitra.pengaturan.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="p-6 sm:p-8 space-y-8">
                    
                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-store text-blue-600 mr-2"></i> Profil & Lokasi</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Rental <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_rental" value="<?php echo e(old('nama_rental', $rental->nama_rental)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Lengkap (Untuk Google Maps) <span class="text-red-500">*</span></label>
                                <textarea name="alamat" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: Jl. Sudirman No 12, Kec. Tampan, Kota Pekanbaru" required><?php echo e(old('alamat', $rental->alamat)); ?></textarea>
                                <p class="text-xs text-gray-400 mt-1">Pastikan alamat akurat agar titik Google Maps di halaman pemesanan sesuai.</p>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-money-check-dollar text-green-600 mr-2"></i> Rekening Pencairan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Bank</label>
                                <input type="text" name="nama_bank" value="<?php echo e(old('nama_bank', $rental->nama_bank)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Contoh: BCA / MANDIRI">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Rekening</label>
                                <input type="number" name="no_rekening" value="<?php echo e(old('no_rekening', $rental->no_rekening)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="1234567890">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Atas Nama</label>
                                <input type="text" name="atas_nama_rekening" value="<?php echo e(old('atas_nama_rekening', $rental->atas_nama_rekening)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-semibold focus:ring-blue-500" placeholder="Nama Pemilik Rekening">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-tags text-indigo-600 mr-2"></i> Biaya Layanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Sopir / Hari</label>
                                <input type="number" name="biaya_sopir_per_hari" value="<?php echo e(old('biaya_sopir_per_hari', $rental->biaya_sopir_per_hari ?? 0)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="150000" min="0">
                                <p class="text-xs text-gray-400 mt-1">Dipakai saat pelanggan memilih opsi Dengan Sopir.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Biaya Bandara / Trip</label>
                                <input type="number" name="biaya_bandara_per_trip" value="<?php echo e(old('biaya_bandara_per_trip', $rental->biaya_bandara_per_trip ?? 0)); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="100000" min="0">
                                <p class="text-xs text-gray-400 mt-1">Dipakai untuk Jemput di Bandara dan Antar ke Bandara.</p>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-gray-100 pb-2 mb-4"><i class="fa-solid fa-file-contract text-orange-500 mr-2"></i> Syarat & Ketentuan Sewa</h3>
                        <div>
                            <textarea name="syarat_ketentuan" rows="8" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 font-medium text-sm focus:ring-blue-500" placeholder="1. Penyewa wajib menyerahkan KTP asli...&#10;2. Dilarang merokok di dalam mobil..."><?php echo e(old('syarat_ketentuan', $rental->syarat_ketentuan)); ?></textarea>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/pengaturan.blade.php ENDPATH**/ ?>