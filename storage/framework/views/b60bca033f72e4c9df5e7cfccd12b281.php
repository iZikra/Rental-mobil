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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-slate-800">Daftar Mitra (Tenant)</h2>
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-bold text-sm">Nama Rental</th>
                            <th class="px-6 py-4 font-bold text-sm">Pemilik</th>
                            <th class="px-6 py-4 font-bold text-sm">Status</th>
                            <th class="px-6 py-4 font-bold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b">
                            <td class="px-6 py-4 font-semibold"><?php echo e($rental->nama_rental); ?></td>
                            <td class="px-6 py-4"><?php echo e($rental->user->name); ?></td>
                            <td class="px-6 py-4">
                                <?php if($rental->status == 'active'): ?>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Aktif</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($rental->status == 'pending'): ?>
                                <form action="<?php echo e(route('admin.rentals.approve', $rental->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="bg-indigo-600 text-white px-4 py-1 rounded-lg text-xs font-bold hover:bg-indigo-700">Approve</button>
                                </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.rentals.block', $rental->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="text-rose-600 ml-3 font-bold text-xs">Block</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/admin/rentals/index.blade.php ENDPATH**/ ?>