<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Multi Rent Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .parallax {
            background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=2070&auto=format&fit=crop');
            height: 100vh;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,.75), rgba(0,0,0,.35));
        }
    </style>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    <div class="hidden lg:block lg:w-1/2 parallax">
        <div class="overlay flex flex-col justify-center px-16 text-white">
            <h1 class="text-5xl font-bold mb-6">
                Platform Multi Rental Mobil
            </h1>
            <p class="text-lg text-gray-200 max-w-xl mb-6">
                Masuk untuk lanjut booking atau kelola rental melalui dashboard.
            </p>
            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-white"></span> <span>Pilihan mobil dari banyak mitra</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-white"></span> <span>Kelola armada & pesanan untuk mitra</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-white"></span> <span>Tersedia di berbagai kota</span>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-10 bg-white">
        <div class="w-full max-w-md">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-sm font-semibold text-slate-500">Multi Rent Platform</div>
                    <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
                        Masuk
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Masuk untuk lanjut booking atau akses dashboard mitra.
                    </p>
                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    Email atau kata sandi yang Anda masukkan salah.
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5 mt-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Alamat Email</label>
                    <input type="email" name="email" required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                           placeholder="email@contoh.com" value="<?php echo e(old('email')); ?>">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input type="password" name="password" required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-red-500 focus:ring-4 focus:ring-red-100"
                           placeholder="••••••••">
                </div>

                <div class="flex justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="text-red-600 focus:ring-red-500 rounded border-gray-300">
                        <span class="text-slate-600">Ingat saya</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="text-red-500 hover:text-red-700 font-medium transition">
                        Lupa password?
                    </a>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-red-600 to-red-800 py-3 font-extrabold text-white shadow-lg transition hover:opacity-95 focus:outline-none focus:ring-4 focus:ring-red-100">
                    Masuk
                </button>

                <div class="pt-2 text-center text-sm text-slate-600">
                    Belum punya akun?
                    <a href="<?php echo e(route('register')); ?>" class="ml-1 font-extrabold text-red-700 hover:text-red-800 hover:underline">
                        Daftar Penyewa
                    </a>
                    <div class="mt-2">
                        Punya rental dan mau jadi mitra?
                        <a href="<?php echo e(route('mitra.register')); ?>" class="ml-1 font-bold text-slate-700 hover:underline">
                            Daftar Mitra
                        </a>
                    </div>
                </div>
            </form>

            <div class="mt-8 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                Masuk untuk melanjutkan aktivitas kamu di platform ini.
            </div>
        </div>
    </div>

</div>

</body>
</html>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/auth/login.blade.php ENDPATH**/ ?>