<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - FZ Rent Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col lg:flex-row">
        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1494976388531-d1058494cdd8?q=80&w=2070&auto=format&fit=crop');">
            
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            
            <div class="absolute bottom-0 left-0 p-12 text-white">
                <h1 class="text-4xl font-bold mb-4">Selamat Datang Kembali!</h1>
                <p class="text-lg bg-black bg-opacity-20 inline-block p-2 rounded">Siap untuk perjalanan Anda berikutnya? Masuk untuk mulai menyewa.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 flex items-center justify-center gap-2">
                        🚗 FZ RENT CAR
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Belum punya akun? 
                        <?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register')); ?>" class="font-medium text-red-600 hover:text-red-500 transition">
                                Daftar di sini
                            </a>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">Login Gagal. Cek kembali email & password.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form class="mt-8 space-y-6" method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm" 
                                   placeholder="contoh@email.com" value="<?php echo e(old('email')); ?>">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                   class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm" 
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Ingat saya
                            </label>
                        </div>

                        <?php if(Route::has('password.request')): ?>
                            <div class="text-sm">
                                <a href="<?php echo e(route('password.request')); ?>" class="font-medium text-red-600 hover:text-red-500 transition">
                                    Lupa password?
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200 transform hover:scale-[1.02]">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-red-300 group-hover:text-red-200 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Masuk Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\auth\login.blade.php ENDPATH**/ ?>