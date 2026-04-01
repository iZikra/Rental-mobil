<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-xl">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
                Bergabung Menjadi Mitra Rental
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Perluas jangkauan sewa mobil Anda melintasi kota bersama kami.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-gray-100">
                
                
                <?php if(session('error')): ?>
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg text-sm font-bold border border-red-200">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg text-sm font-bold border border-red-200">
                        <ul class="list-disc pl-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('mitra.register.submit')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-user-shield text-blue-600"></i> 1. Informasi Akun Pengelola
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">No. WhatsApp</label>
                                <input type="text" name="no_hp" value="<?php echo e(old('no_hp')); ?>" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700">Email Aktif</label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Password</label>
                                <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-building text-blue-600"></i> 2. Profil Bisnis Rental
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Nama Rental / Perusahaan</label>
                                <input type="text" name="nama_rental" value="<?php echo e(old('nama_rental')); ?>" required placeholder="Contoh: Berkah Jaya Rent" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Kota Basis Operasional</label>
                                <input type="text" name="kota" value="<?php echo e(old('kota')); ?>" required placeholder="Contoh: Pekanbaru" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-extrabold text-white bg-slate-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                            Daftarkan Mitra Sekarang
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah menjadi mitra? <a href="<?php echo e(route('login')); ?>" class="font-bold text-blue-600 hover:text-blue-500">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/auth/mitra-register.blade.php ENDPATH**/ ?>