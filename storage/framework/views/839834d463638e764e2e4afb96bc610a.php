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
    <div class="bg-gray-900 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-serif font-bold text-white tracking-wide">
                TAMBAH <span class="text-red-600">ARMADA BARU</span>
            </h1>
            <p class="text-gray-400 mt-2 text-sm">Masukkan detail mobil baru ke dalam database.</p>
        </div>
    </div>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-600">
                <div class="p-8">

                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                            <p class="font-bold">Terjadi Kesalahan!</p>
                            <ul class="list-disc pl-5 mt-2 text-sm">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('mobils.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Merk Mobil</label>
                                <input type="text" name="merk" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Toyota" value="<?php echo e(old('merk')); ?>">
                            </div>

                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Model / Tipe</label>
                                <input type="text" name="model" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Avanza Veloz" value="<?php echo e(old('model')); ?>">
                            </div>

                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Nomor Plat</label>
                                <input type="text" name="no_plat" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 uppercase" placeholder="B 1234 XYZ" value="<?php echo e(old('no_plat')); ?>">
                            </div>

                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Harga Sewa (per Hari)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="harga_sewa" class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="350000" value="<?php echo e(old('harga_sewa')); ?>">
                                </div>
                            </div>

                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Status Ketersediaan</label>
                                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="tersedia">Tersedia (Ready)</option>
                                    <option value="disewa">Sedang Disewa</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-serif font-bold text-gray-700 mb-2">Foto Mobil</label>
                                <input type="file" name="gambar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded-md cursor-pointer">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                            </div>

                        </div>

                        <div>
                            <label class="block font-serif font-bold text-gray-700 mb-2">Deskripsi / Fasilitas</label>
                            <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Tuliskan detail seperti: Matic/Manual, Tahun, Warna, dll..."><?php echo e(old('deskripsi')); ?></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="<?php echo e(route('mobils.index')); ?>" class="text-gray-600 hover:text-gray-900 font-medium">Batal</a>
                            <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-md font-bold hover:bg-red-600 transition shadow-lg">
                                SIMPAN DATA
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\mobil\create.blade.php ENDPATH**/ ?>