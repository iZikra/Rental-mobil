<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveNow - Layanan Rental Mobil Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- 1. HEADER --}}
            <header class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-900 p-3 rounded-lg">
                            <i class="fas fa-car-side text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">DriveNow Rental Mobil</h1>
                            <p class="text-gray-500">Layanan rental mobil premium</p>
                        </div>
                    </div>
                </header>

            {{-- 2. TOGGLE PORTAL PELANGGAN / PANEL ADMIN --}}
            <div class="flex justify-center mb-8">
                <div class="bg-gray-200 p-1 rounded-full flex items-center space-x-1">
                    <a href="#" class="px-6 py-2 text-sm font-semibold text-gray-800 bg-white rounded-full shadow-sm">
                        <i class="fas fa-user mr-2"></i>Portal Pelanggan
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-300 rounded-full">
                        <i class="fas fa-shield-alt mr-2"></i>Panel Admin
                    </a>
                </div>
            </div>

            {{-- 3. KOTAK SELAMAT DATANG --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8 text-center shadow-sm">
                <h2 class="text-2xl font-semibold mb-2">Selamat Datang</h2>
                <p class="text-gray-600">Jelajahi armada kendaraan premium kami dan pesan mobil impian Anda</p>
            </div>

            {{-- 4. TAB DAN FILTER --}}
            <div>
                <div class="border-b border-gray-200">
                    {{-- KODE BARU YANG SUDAH DIPERBAIKI --}}
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        
                        {{-- Link ke Halaman Utama (Homepage) --}}
                        <a href="{{ route('homepage') }}" 
                        class="{{ request()->routeIs('homepage') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium">
                            Daftar Kendaraan
                        </a>
                        
                        {{-- Link ke Halaman Lacak Pesanan --}}
                        <a href="{{ route('booking.lacak.form') }}" 
                        class="{{ request()->routeIs('booking.lacak.form') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 border-b-2 font-medium">
                            Riwayat Pesanan
                        </a>
                    </nav>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 mb-4">
    <h3 class="text-xl font-bold text-gray-900 mb-4 sm:mb-0">
        Kendaraan Tersedia
    </h3>
</div>
            </div>

            {{-- 5. DAFTAR KENDARAAN (GRID) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($cars as $car)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                        <div class="relative">
                            <img src="{{ Storage::url($car->gambar) }}" alt="{{ $car->nama_mobil }}" class="h-56 w-full object-cover group-hover:opacity-80 transition-opacity duration-300">
                        </div>
                        <div class="p-5">
                            <h4 class="text-xl font-bold text-gray-900 truncate">{{ $car->nama_mobil }}</h4>
                            <p class="text-gray-600 mt-1">Harga mulai dari <span class="font-bold text-indigo-600">Rp {{ number_format($car->harga_sewa) }}</span> / hari</p>
                            <a href="{{ route('car.show', $car->slug) }}" class="mt-4 inline-block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                                Lihat Detail & Pesan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-lg shadow-lg">
                        <i class="fas fa-car-crash text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700">Oops! Belum Ada Mobil yang Tersedia</h3>
                        <p class="text-gray-500 mt-2">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} DriveNow Rental Mobil. Hak cipta dilindungi.
            </p>
        </div>
    </footer>
</body>
</html>