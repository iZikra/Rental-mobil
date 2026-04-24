<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - FZ Rent Car</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1470136940843-1678b87a93cb?q=80&w=2070&auto=format&fit=crop');">
            
            <div class="absolute inset-0 bg-gray-900 bg-opacity-60"></div>
            
            <div class="absolute bottom-0 left-0 p-12 text-white">
                <h1 class="text-4xl font-bold mb-4">Lupa Kata Sandi?</h1>
                <p class="text-lg bg-black bg-opacity-20 inline-block p-2 rounded">Jangan khawatir. Kami akan membantu Anda mendapatkan kembali akses akun Anda.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">
                
                <div class="text-center">
                    <!-- <div class="flex justify-center mb-6">
                        <img src="<?php echo e(asset('img/logo.png')); ?>" alt="Logo FZ Rent Car" class="h-[90px] w-auto object-contain">
                    </div> -->
                    
                    <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                    <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                        Masukkan alamat email yang Anda gunakan saat mendaftar. Kami akan mengirimkan link untuk mengatur ulang kata sandi Anda.
                    </p>
                </div>

                <?php if(session('status')): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">
                                    Link reset password telah dikirim ke email Anda!
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                        <p class="text-sm text-red-700 font-medium">Email tidak ditemukan atau format salah.</p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" required autofocus
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                               placeholder="contoh@email.com" value="<?php echo e(old('email')); ?>">
                    </div>

                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200 shadow-lg">
                        Kirim Link Reset
                    </button>

                    <div class="flex items-center justify-center mt-4">
                        <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium text-gray-600 hover:text-red-600 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>