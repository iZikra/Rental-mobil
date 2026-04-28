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
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Armada Baru</h2>

                    
                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md shadow-sm">
                            <h3 class="font-bold text-red-800 mb-2">Gagal Menyimpan Data:</h3>
                            <ul class="text-sm text-red-700 list-disc list-inside">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    
                    <form action="<?php echo e(route('mitra.mobil.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Cabang</label>
                                <select name="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->nama_cabang); ?> - <?php echo e($branch->kota); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <a href="<?php echo e(route('mitra.mobil.index')); ?>" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded hover:bg-gray-300 transition">
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
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/mobil/create.blade.php ENDPATH**/ ?>