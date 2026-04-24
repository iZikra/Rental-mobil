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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                <?php echo e(__('Manajemen Master Wilayah & Cabang')); ?>

            </h2>
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                <i class="fas fa-plus mr-2"></i> Tambah Wilayah Baru
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                            <th class="px-6 py-4 font-bold">Nama Cabang</th>
                            <th class="px-6 py-4 font-bold">Kota</th>
                            <th class="px-6 py-4 font-bold">Alamat Lengkap</th>
                            <th class="px-6 py-4 font-bold">Telepon</th>
                            <th class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium"><?php echo e($branch->nama_cabang); ?></td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        <?php echo e($branch->kota); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm"><?php echo e(Str::limit($branch->alamat_lengkap, 50)); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($branch->nomor_telepon_cabang); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <form action="<?php echo e(route('admin.branches.destroy', $branch->id)); ?>" method="POST" onsubmit="return confirm('Hapus wilayah ini? Ini akan berdampak pada mobil yang terhubung.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data wilayah. Silakan tambah data baru.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-slate-900/50 hidden backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-4">Tambah Master Wilayah</h3>
            
            <form action="<?php echo e(route('admin.branches.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Cabang / Wilayah</label>
                        <input type="text" name="nama_cabang" placeholder="Contoh: Cabang Pusat" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Kota</label>
                        <input type="text" name="kota" placeholder="Contoh: Jakarta" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="3" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon_cabang" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:text-slate-800 transition">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition">Simpan Data</button>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\branches\index.blade.php ENDPATH**/ ?>