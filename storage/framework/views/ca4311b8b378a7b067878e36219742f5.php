<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Multi Rent Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f172a;
            overflow-x: hidden;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .bg-animated {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #0ea5e9, #0284c7);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating-shape {
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape-delayed {
            animation: float 8s ease-in-out infinite 2s;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px white inset !important;
        }
    </style>
</head>

<body class="bg-animated min-h-screen flex items-center justify-center p-4 relative antialiased">
    
    
    <div class="absolute top-[10%] left-[10%] w-64 h-64 bg-blue-500/30 rounded-full blur-3xl floating-shape mix-blend-screen pointer-events-none"></div>
    <div class="absolute bottom-[20%] right-[10%] w-80 h-80 bg-cyan-400/20 rounded-full blur-3xl floating-shape-delayed mix-blend-screen pointer-events-none"></div>
    <div class="absolute top-[40%] right-[30%] w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl floating-shape mix-blend-screen pointer-events-none"></div>

    <div class="w-full max-w-xl flex flex-col glass-panel rounded-3xl overflow-hidden relative z-10 transition-all duration-300 transform shadow-2xl">
        
        
        <div class="w-full p-8 sm:p-12 lg:p-16 flex flex-col justify-center relative bg-white">
            
            <a href="<?php echo e(route('home')); ?>" class="lg:hidden absolute top-6 left-6 text-slate-400 hover:text-slate-700 transition flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>

            <div class="mb-10 text-center lg:text-left mt-6 lg:mt-0">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang 👋</h2>
                <p class="text-slate-500 text-sm mt-3 font-medium">Silakan login untuk melanjutkan booking atau masuk ke dashboard Mitra Anda.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 animate-pulse">
                    <div class="text-red-500 mt-0.5"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Login Gagal</h3>
                        <p class="text-xs text-red-600 mt-1">Email atau kata sandi yang Anda masukkan tidak cocok dengan sistem kami.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required autofocus
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="emailanda@contoh.com" value="<?php echo e(old('email')); ?>">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" required
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                        <span class="text-sm font-medium text-slate-500 group-hover:text-slate-800 transition">Ingat saya</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline transition">Lupa Sandi?</a>
                </div>

                <button type="submit"
                        class="w-full bg-slate-900 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-600/30 text-white rounded-2xl py-4 text-sm font-bold tracking-wide transition-all duration-300 group flex justify-center items-center gap-2 transform active:scale-[0.98]">
                    <span>Masuk ke Akun</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-8 text-center bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Belum memiliki akun?</p>
                <div class="mt-2 flex flex-col sm:flex-row justify-center gap-3 items-center text-sm font-bold">
                    <a href="<?php echo e(route('register')); ?>" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                        <i class="fa-regular fa-user"></i> Daftar Customer
                    </a>
                    <span class="hidden sm:inline text-slate-300">|</span>
                    <a href="<?php echo e(route('mitra.register')); ?>" class="text-slate-700 hover:text-blue-600 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-handshake"></i> Jadi Mitra Rental
                    </a>
                </div>
            </div>

        </div>
    </div>
    
</body>
</html>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/auth/login.blade.php ENDPATH**/ ?>