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
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Edit Mobil: <?php echo e($mobil->merk); ?> <?php echo e($mobil->model); ?></h2>
                        <a href="<?php echo e(route('mitra.mobil.index')); ?>" class="text-gray-500 hover:text-gray-700 font-medium">&larr; Kembali</a>
                    </div>

                    
                    <form action="<?php echo e(route('mitra.mobil.update', $mobil->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Cabang</label>
                                <select name="branch_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($branch->id); ?>" <?php echo e($mobil->branch_id == $branch->id ? 'selected' : ''); ?>>
                                            <?php echo e($branch->nama_cabang); ?> - <?php echo e($branch->kota); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Merk Mobil</label>
                                <input type="text" name="merk" value="<?php echo e(old('merk', $mobil->merk)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Model / Tipe</label>
                                <input type="text" name="model" value="<?php echo e(old('model', $mobil->model)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Plat</label>
                                <input type="text" name="no_plat" value="<?php echo e(old('no_plat', $mobil->no_plat)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Pembuatan</label>
                                <input type="number" name="tahun_buat" value="<?php echo e(old('tahun_buat', $mobil->tahun_buat)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Transmisi</label>
                                <select name="transmisi" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Manual" <?php echo e($mobil->transmisi == 'Manual' ? 'selected' : ''); ?>>Manual</option>
                                    <option value="Matic" <?php echo e($mobil->transmisi == 'Matic' ? 'selected' : ''); ?>>Matic</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Bahan Bakar</label>
                                <select name="bahan_bakar" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Bensin" <?php echo e($mobil->bahan_bakar == 'Bensin' ? 'selected' : ''); ?>>Bensin</option>
                                    <option value="Solar" <?php echo e($mobil->bahan_bakar == 'Solar' ? 'selected' : ''); ?>>Solar</option>
                                    <option value="Listrik" <?php echo e($mobil->bahan_bakar == 'Listrik' ? 'selected' : ''); ?>>Listrik</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kursi</label>
                                <input type="number" name="jumlah_kursi" value="<?php echo e(old('jumlah_kursi', $mobil->jumlah_kursi)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa (Per Hari)</label>
                                <input type="number" name="harga_sewa" value="<?php echo e(old('harga_sewa', $mobil->harga_sewa)); ?>" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto (Opsional)</label>
                                <input type="file" name="gambar" class="w-full border border-gray-300 rounded-md p-2">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                                
                                <?php if($mobil->gambar): ?>
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-600 mb-1">Foto Saat Ini:</p>
                                        <img src="<?php echo e(asset('storage/' . $mobil->gambar)); ?>" class="h-24 w-auto rounded border">
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="<?php echo e(route('mitra.mobil.index')); ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow-lg">
                                Update Data Mobil
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/mitra/mobil/edit.blade.php ENDPATH**/ ?>