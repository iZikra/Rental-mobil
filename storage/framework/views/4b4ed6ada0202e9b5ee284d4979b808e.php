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
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Edit Pemesanan #<?php echo e($reservation->id); ?>

        </h2>
        <a href="<?php echo e(route('reservations.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Detail Pemesanan</h3>
            <p class="text-gray-600">Pelanggan: <?php echo e($reservation->customer->nama_customer); ?></p>
            <p class="text-gray-600">Mobil: <?php echo e($reservation->car->nama_mobil); ?></p>
            <p class="text-gray-600">Tanggal: <?php echo e(\Carbon\Carbon::parse($reservation->tanggal_mulai)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($reservation->tanggal_selesai)->format('d M Y')); ?></p>
        </div>

        <form action="<?php echo e(route('reservations.update', $reservation->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                <select name="status" id="status" required class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="menunggu" <?php echo e($reservation->status == 'menunggu' ? 'selected' : ''); ?>>
                        Menunggu
                    </option>
                    <option value="dikonfirmasi" <?php echo e($reservation->status == 'dikonfirmasi' ? 'selected' : ''); ?>>
                        Dikonfirmasi
                    </option>
                    <option value="selesai" <?php echo e($reservation->status == 'selesai' ? 'selected' : ''); ?>>
                        Selesai
                    </option>
                    <option value="dibatalkan" <?php echo e($reservation->status == 'dibatalkan' ? 'selected' : ''); ?>>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="mt-8 text-left">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\reservations\edit.blade.php ENDPATH**/ ?>