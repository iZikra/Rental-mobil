<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Mitra - Multi Rent Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .parallax { background-attachment: fixed; }
    </style>
</head>

<body class="bg-gray-100">
<div class="min-h-screen flex flex-col lg:flex-row">
    <div class="hidden lg:block lg:w-1/2 bg-cover bg-center relative parallax"
         style="background-image: url('https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=2070&auto=format&fit=crop');">

        <div class="absolute inset-0 bg-black bg-opacity-50"></div>

        <div class="absolute bottom-0 left-0 p-12 text-white">
            <h1 class="text-4xl font-bold mb-4">Bergabung Bersama Kami</h1>
            <p class="text-lg bg-black bg-opacity-20 inline-block p-2 rounded">
                Kelola rental, cabang, armada, dan pesanan dari satu dashboard.
            </p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 bg-white">
        <div class="w-full max-w-md space-y-6">

            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">Daftar Mitra</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login', ['as' => 'mitra']) }}" class="font-medium text-red-600 hover:text-red-500 transition">
                        Masuk di sini
                    </a>
                </p>
            </div>

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="text-sm text-red-700 font-semibold">{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <ul class="list-disc pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('mitra.register.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required autofocus
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Nama pengelola" value="{{ old('name') }}">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input id="email" name="email" type="email" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="contoh@email.com" value="{{ old('email') }}">
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP (WhatsApp)</label>
                    <input id="no_hp" name="no_hp" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Contoh: 081234567890" value="{{ old('no_hp') }}">
                </div>

                <div>
                    <label for="nama_rental" class="block text-sm font-medium text-gray-700 mb-1">Nama Rental / Perusahaan</label>
                    <input id="nama_rental" name="nama_rental" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Contoh: Berkah Jaya Rent" value="{{ old('nama_rental') }}">
                </div>

                <div>
                    <label for="nama_cabang" class="block text-sm font-medium text-gray-700 mb-1">Nama Cabang Utama</label>
                    <input id="nama_cabang" name="nama_cabang" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Contoh: Cabang Pekanbaru Kota" value="{{ old('nama_cabang') }}">
                </div>

                <div>
                    <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota Cabang</label>
                    <input id="kota" name="kota" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Contoh: Pekanbaru" value="{{ old('kota') }}">
                </div>

                <div>
                    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap Cabang</label>
                    <input id="alamat_lengkap" name="alamat_lengkap" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Jalan, kelurahan, kota..." value="{{ old('alamat_lengkap') }}">
                </div>

                <div>
                    <label for="nomor_telepon_cabang" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Cabang</label>
                    <input id="nomor_telepon_cabang" name="nomor_telepon_cabang" type="text" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Contoh: 081234567890" value="{{ old('nomor_telepon_cabang') }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                           placeholder="Ketik ulang sandi">
                </div>

                <div class="pt-2">
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200 transform hover:scale-[1.02] shadow-lg">
                        Daftar Mitra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
</html>
