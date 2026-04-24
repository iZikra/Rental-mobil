<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Mitra - Multi Rent Platform</title>
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
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .bg-animated {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #0ea5e9, #0369a1);
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body class="bg-animated min-h-screen flex items-center justify-center p-4 lg:p-12 relative antialiased">
    
    
    <div class="absolute top-[-10%] left-[-5%] w-[40rem] h-[40rem] bg-blue-600/20 rounded-full blur-[100px] floating-shape pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-[35rem] h-[35rem] bg-cyan-500/10 rounded-full blur-[100px] floating-shape-delayed pointer-events-none"></div>

    <div class="w-full max-w-3xl glass-panel rounded-[2.5rem] overflow-hidden relative z-10 shadow-[0_32px_64px_-15px_rgba(0,0,0,0.3)] border border-white/40">
        
        <div class="w-full p-8 sm:p-12 lg:p-14 flex flex-col relative bg-white/95 backdrop-blur-md custom-scrollbar overflow-y-auto max-h-[90vh]">
            
            
            <a href="<?php echo e(route('home')); ?>" class="absolute top-8 left-8 w-10 h-10 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl flex items-center justify-center transition-all duration-300 group shadow-sm">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>

            
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-6 text-blue-600 shadow-inner">
                    <i class="fa-solid fa-handshake text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Daftar Mitra Baru</h2>
                <p class="text-slate-500 text-sm font-medium">
                    Lengkapi data di bawah untuk bergabung sebagai mitra profesional kami.
                </p>
                <div class="mt-4 inline-block px-4 py-2 bg-blue-50/50 rounded-full border border-blue-100/50">
                    <p class="text-[11px] text-blue-600 font-bold tracking-wide uppercase">
                        Sudah punya akun mitra? 
                        <a href="<?php echo e(route('login', ['as' => 'mitra'])); ?>" class="text-blue-700 hover:underline ml-1 font-extrabold">Masuk Sekarang</a>
                    </p>
                </div>
            </div>

            <?php if(session('error')): ?>
                <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <p class="text-sm text-red-800 font-bold"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl">
                    <div class="flex items-center gap-3 mb-3 text-red-800">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3 class="text-sm font-extrabold">Pendaftaran Terkendala</h3>
                    </div>
                    <ul class="space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="text-xs text-red-600 font-medium flex items-center gap-2">
                                <span class="w-1 h-1 bg-red-400 rounded-full"></span> <?php echo e($error); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('mitra.register.submit')); ?>" class="space-y-8">
                <?php echo csrf_field(); ?>

                
                <div>
                    <div class="flex items-center gap-4 my-8">
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Informasi Pengelola</span>
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <input type="text" name="name" required autofocus class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Nama Pengelola" value="<?php echo e(old('name')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <input type="email" name="email" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="email@bisnis.com" value="<?php echo e(old('email')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                </div>
                                <input type="text" name="no_hp" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: 081234567890" value="<?php echo e(old('no_hp')); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex items-center gap-4 my-8">
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Informasi Bisnis & Cabang</span>
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Rental</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-car-rear"></i>
                                </div>
                                <input type="text" name="nama_rental" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: Berkah Jaya Rent" value="<?php echo e(old('nama_rental')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Cabang</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                                <input type="text" name="nama_cabang" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Contoh: Cabang Kota" value="<?php echo e(old('nama_cabang')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Kota Operasional</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-city"></i>
                                </div>
                                <input type="text" name="kota" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Pekanbaru, Jakarta, dll" value="<?php echo e(old('kota')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Telepon Cabang</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <input type="text" name="nomor_telepon_cabang" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Nomor Kantor" value="<?php echo e(old('nomor_telepon_cabang')); ?>">
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap Cabang</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <input type="text" name="alamat_lengkap" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Jalan, Kelurahan, Kecamatan..." value="<?php echo e(old('alamat_lengkap')); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex items-center gap-4 my-8">
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                        <span class="text-content text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Keamanan Akun</span>
                        <span class="flex-1 h-[1px] bg-slate-100"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Kata Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" name="password" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Minimal 8 karakter">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <input type="password" name="password_confirmation" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Ulangi kata sandi">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-[1.25rem] py-4 text-sm font-extrabold tracking-wider transition-all duration-300 shadow-[0_20px_40px_-10px_rgba(37,99,235,0.4)] hover:shadow-[0_25px_50px_-12px_rgba(37,99,235,0.5)] transform active:scale-[0.98] flex items-center justify-center gap-3">
                        <span>DAFTAR SEBAGAI MITRA</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    
                    <div class="mt-8 text-center">
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed max-w-sm mx-auto">
                            Dengan mendaftar, Anda menyatakan setuju dengan 
                            <a href="#" class="text-blue-500 hover:underline">Syarat & Ketentuan Mitra</a> 
                            serta <a href="#" class="text-blue-500 hover:underline">Kebijakan Privasi</a> kami.
                        </p>
                    </div>
                </div>
            </form>

        </div>
    </div>
    
</body>
</html>
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\auth\mitra-register.blade.php ENDPATH**/ ?>