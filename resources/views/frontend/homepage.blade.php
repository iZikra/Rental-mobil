<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveNow - Layanan Rental Mobil Premium</title>

    {{-- PWA Meta Tags --}}
    <meta name="theme-color" content="#3b82f6">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/3202/3202926.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menggunakan Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
    <!-- Group Pencarian -->
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <!-- Smart Search (AI) -->
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-wand-magic-sparkles text-indigo-500"></i>
            </div>
            <input type="text" id="smart-search" placeholder="Tanya AI: 'mobil keluarga irit'..." 
                   class="block w-full pl-10 pr-12 rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2" autocomplete="off" />
            <button id="btn-smart-search" class="absolute inset-y-0 right-0 pr-3 flex items-center text-indigo-600 hover:text-indigo-800 transition">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>

        <!-- Location Search Autocomplete -->
        <div class="relative w-full sm:w-48">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-location-dot text-gray-400"></i>
            </div>
            <input type="text" id="location-search" placeholder="Cari lokasi..." 
                   class="block w-full pl-9 rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2" autocomplete="off" />
            <ul id="location-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 hidden max-h-48 overflow-y-auto shadow-lg">
                <!-- suggestions will be injected here -->
            </ul>
        </div>
    </div>
</div>

<!-- Smart Search Results (AI Recommendations) -->
<div id="smart-search-results" class="hidden mb-8 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl animate-fade-in">
    <div class="flex items-center gap-2 mb-3">
        <i class="fa-solid fa-robot text-indigo-600"></i>
        <span class="font-bold text-indigo-900 text-sm">Rekomendasi AI untuk Anda:</span>
        <button id="close-smart-search" class="ml-auto text-indigo-400 hover:text-indigo-600">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <!-- AI Summary Text -->
    <div id="ai-summary" class="text-xs text-indigo-800 mb-4 font-medium leading-relaxed bg-white/50 p-3 rounded-xl border border-indigo-100 hidden"></div>

    <div id="ai-recommendations-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- AI cards will be injected here -->
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
</style>
            </div>

            {{-- 5. DAFTAR KENDARAAN (GRID) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($cars as $car)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group">
                        <div class="relative">
                            <img src="{{ $car->image_url }}" alt="{{ $car->merk }} {{ $car->model }}" class="h-56 w-full object-cover group-hover:opacity-80 transition-opacity duration-300">
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
    
    {{-- SCRIPTS --}}
    <script>
        // === SMART SEARCH AI ===
        const smartSearchInput = document.getElementById('smart-search');
        const btnSmartSearch = document.getElementById('btn-smart-search');
        const smartSearchResults = document.getElementById('smart-search-results');
        const aiRecommendationsList = document.getElementById('ai-recommendations-list');
        const aiSummaryText = document.getElementById('ai-summary');
        const closeSmartSearch = document.getElementById('close-smart-search');

        function doSmartSearch() {
            const query = smartSearchInput.value.trim();
            if (query.length < 3) return;

            // Ambil filter kota jika ada
            const selectedCity = document.getElementById('kota')?.value || '';

            btnSmartSearch.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btnSmartSearch.disabled = true;

            fetch("{{ route('chatbot.smart_search') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ 
                    query_input: query,
                    selected_city: selectedCity
                })
            })
            .then(res => res.json())
            .then(res => {
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
                btnSmartSearch.disabled = false;

                if (res.status === 'success' && res.data.length > 0) {
                    smartSearchResults.classList.remove('hidden');
                    aiRecommendationsList.innerHTML = '';
                    
                    // Set Summary
                    if (res.summary) {
                        aiSummaryText.innerHTML = `<strong>Ringkasan:</strong> ${res.summary}`;
                        aiSummaryText.classList.remove('hidden');
                    } else {
                        aiSummaryText.classList.add('hidden');
                    }
                    
                    res.data.forEach(item => {
                        const card = `
                            <div class="group bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col animate-fade-in-up">
                                <div class="relative h-40 overflow-hidden">
                                    <img src="${item.gambar}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="${item.nama}">
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-white/90 backdrop-blur-sm text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm border border-indigo-50">
                                            ${item.tipe}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-slate-900 text-sm truncate pr-2">${item.nama}</h5>
                                        <span class="text-indigo-600 font-bold text-xs whitespace-nowrap">Rp ${item.harga}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-[10px] text-slate-500 mb-3">
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot text-indigo-400"></i> ${item.kota}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-gear text-indigo-400"></i> ${item.transmisi}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-user-group text-indigo-400"></i> ${item.kursi}</span>
                                    </div>
                                    
                                    ${item.scores ? `
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="bg-emerald-50 text-emerald-700 text-[9px] px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1">
                                            <i class="fa-solid fa-gas-pump"></i> ${item.scores.bbm}
                                        </span>
                                        <span class="bg-amber-50 text-amber-700 text-[9px] px-2 py-0.5 rounded-full border border-amber-100 flex items-center gap-1">
                                            <i class="fa-solid fa-tag"></i> Harga: ${item.scores.harga}
                                        </span>
                                        <span class="bg-blue-50 text-blue-700 text-[9px] px-2 py-0.5 rounded-full border border-blue-100 flex items-center gap-1">
                                            <i class="fa-solid fa-users"></i> Kapasitas: ${item.scores.kapasitas}
                                        </span>
                                    </div>
                                    ` : ''}

                                    <div class="bg-indigo-50/50 rounded-xl p-3 mb-4 flex-1">
                                        <p class="text-[11px] text-indigo-700 font-medium leading-relaxed italic line-clamp-3">
                                            <i class="fa-solid fa-quote-left text-[8px] opacity-50 mr-1"></i>
                                            ${item.reason}
                                        </p>
                                    </div>
                                    <a href="${item.booking_url}" target="_blank" class="w-full bg-indigo-600 text-white text-center py-2.5 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                        Detail & Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        `;
                        aiRecommendationsList.innerHTML += card;
                    });
                    
                    // Scroll to results
                    smartSearchResults.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ditemukan',
                        text: 'Maaf, AI tidak menemukan mobil yang sesuai dengan kriteria tersebut di stok saat ini.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                btnSmartSearch.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
                btnSmartSearch.disabled = false;
            });
        }

        btnSmartSearch.addEventListener('click', doSmartSearch);
        smartSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') doSmartSearch();
        });
        closeSmartSearch.addEventListener('click', () => {
            smartSearchResults.classList.add('hidden');
        });

        // === SERVICE WORKER (PWA) ===
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>