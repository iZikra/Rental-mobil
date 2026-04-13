<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - Multi Rent Platform</title>
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

        /* Custom Scrollbar for form if it gets too long */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-animated min-h-screen flex items-center justify-center p-4 lg:p-8 relative antialiased">
    
    {{-- ABSTRACT BACKGROUND SHAPES --}}
    <div class="absolute top-[5%] left-[5%] w-64 h-64 bg-blue-500/30 rounded-full blur-3xl floating-shape mix-blend-screen pointer-events-none"></div>
    <div class="absolute bottom-[10%] right-[5%] w-80 h-80 bg-cyan-400/20 rounded-full blur-3xl floating-shape-delayed mix-blend-screen pointer-events-none"></div>
    <div class="absolute top-[30%] right-[20%] w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl floating-shape mix-blend-screen pointer-events-none"></div>

    <div class="w-full max-w-2xl flex flex-col glass-panel rounded-3xl overflow-hidden relative z-10 transition-all duration-300 shadow-2xl">
        
        {{-- BAGIAN REGISTER --}}
        <div class="w-full p-6 sm:p-10 lg:p-12 flex flex-col justify-center relative bg-white custom-scrollbar overflow-y-auto max-h-[90vh] lg:max-h-none">
            
            <a href="{{ route('home') }}" class="lg:hidden absolute top-6 left-6 text-slate-400 hover:text-slate-700 transition flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>

            <div class="mb-8 text-center lg:text-left mt-4 lg:mt-0">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Buat Akun Baru ✨</h2>
                <p class="text-slate-500 text-sm mt-2 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline ml-1">Masuk di sini</a>
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
                    <div class="text-red-500 mt-0.5"><i class="fa-solid fa-circle-exclamation"></i></div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-red-800">Pendaftaran Terkendala</h3>
                        <ul class="text-xs text-red-600 mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                @csrf

                {{-- 1. Nama Lengkap --}}
                <div class="space-y-2 md:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <input type="text" name="name" required autofocus
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="Nama Lengkap" value="{{ old('name') }}">
                    </div>
                </div>

                {{-- 2. Email --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                </div>

                {{-- 3. Nomor HP --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Nomor WhatsApp</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <input type="text" name="no_hp" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="Contoh: 081234..." value="{{ old('no_hp') }}">
                    </div>
                </div>

                {{-- 4. Tempat Lahir --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Tempat Lahir</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-solid fa-city"></i>
                        </div>
                        <input type="text" name="tempat_lahir" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="Kota Lahir" value="{{ old('tempat_lahir') }}">
                    </div>
                </div>

                {{-- 5. Tanggal Lahir --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Tanggal Lahir</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <input type="date" name="tanggal_lahir" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>

                {{-- 6. Password --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="Min. 8 karakter">
                    </div>
                </div>

                {{-- 7. Konfirmasi Password --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Ulangi Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <input type="password" name="password_confirmation" required
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm font-semibold outline-none transition-all focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                               placeholder="Konfirmasi sandi">
                    </div>
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit"
                            class="w-full bg-slate-900 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-600/30 text-white rounded-2xl py-4 text-sm font-bold tracking-wide transition-all duration-300 group flex justify-center items-center gap-2 transform active:scale-[0.98] shadow-md">
                        <span>Buat Akun Sekarang</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-400 mt-6 leading-relaxed font-medium">
                        Dengan mendaftar, Anda menyatakan telah membaca dan menyetujui <br>
                        <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a> platform kami.
                    </p>
                </div>
            </form>

        </div>
    </div>
    
</body>
</html>
