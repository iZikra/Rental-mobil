<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{'bg-slate-900/80 backdrop-blur-xl border-white/10': scrolled, 'bg-slate-900 border-transparent': !scrolled}"
     class="sticky top-0 z-50 border-b transition-all duration-300 font-sans">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24 items-center">
            
            {{-- LOGO --}}
            <div class="shrink-0 flex items-center gap-3">
                <!-- <a href="{{ route('dashboard') }}" class="group">
                    <img src="{{ asset('img/logo1.png') }}" alt="Logo" 
                         class="block h-16 w-auto object-contain transition transform group-hover:scale-110 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]" />
                </a> -->
                <!-- <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-white tracking-tighter">FZ<span class="text-blue-500">RENT</span></h1>
                </div> -->
            </div>

            {{-- MENU DESKTOP --}}
            <div class="hidden sm:flex sm:items-center sm:ml-10 sm:space-x-8">
                
                @php
                    $navClass = "text-sm font-bold tracking-widest uppercase transition duration-300 py-2 border-b-2";
                    $activeClass = "text-blue-400 border-blue-400 drop-shadow-[0_0_8px_rgba(59,130,246,0.5)]";
                    $inactiveClass = "text-gray-400 border-transparent hover:text-white hover:border-gray-500";
                @endphp

                {{-- 1. BERANDA }}
                
                <!-- <a href="{{ route('dashboard') }}" class="{{ $navClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                    Beranda
                </a> -->

                {{-- 2. MENU KHUSUS SUPER ADMIN --}}
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.rentals.index') }}" class="{{ $navClass }} {{ request()->routeIs('admin.rentals.*') ? $activeClass : $inactiveClass }}">
                        Kelola Mitra
                    </a>
                    
                    <a href="{{ route('admin.branches.index') }}" class="{{ $navClass }} {{ request()->routeIs('admin.branches.*') ? $activeClass : $inactiveClass }}">
                        Master Wilayah
                    </a>

                    <a href="{{ route('admin.transaksi.index') }}" class="{{ $navClass }} {{ request()->routeIs('admin.transaksi.*') ? $activeClass : $inactiveClass }} flex items-center gap-2">
                        Audit Pesanan
                        @php $count = \App\Models\Transaksi::where('status', 'pending')->count(); @endphp
                        @if($count > 0)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white animate-pulse">
                                {{ $count }}
                            </span>
                        @endif
                    </a>
                @endif

                {{-- 3. MENU KHUSUS MITRA (Vendor) --}}
                @if(Auth::user()->role === 'mitra')
                    <a href="{{ route('mitra.dashboard') }}" class="{{ $navClass }} {{ request()->routeIs('mitra.dashboard') ? $activeClass : $inactiveClass }}">
                        Dashboard Mitra
                    </a>
                    
                    <a href="{{ route('mitra.mobil.index') }}" class="{{ $navClass }} {{ request()->routeIs('mitra.mobil.*') ? $activeClass : $inactiveClass }}">
                        Armada Saya
                    </a>

                    <a href="{{ route('mitra.pesanan.index') }}" class="{{ $navClass }} {{ request()->routeIs('mitra.pesanan.*') ? $activeClass : $inactiveClass }}">
                        Pesanan Masuk
                    </a>
                @endif

                {{-- 4. MENU CUSTOMER (User Biasa) --}}
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('dashboard') }}" class="{{ $navClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                        Beranda
                    </a>

                    <a href="{{ route('pages.order') }}" class="{{ $navClass }} {{ request()->routeIs('pages.order') ? $activeClass : $inactiveClass }}">
                        Booking
                    </a>

                    <a href="{{ route('riwayat') }}" class="{{ $navClass }} {{ request()->routeIs('riwayat*') ? $activeClass : $inactiveClass }}">
                        Riwayat Saya
                    </a>

                    <a href="{{ route('pages.about') }}" class="{{ $navClass }} {{ request()->routeIs('pages.about') ? $activeClass : $inactiveClass }}">
                        Tentang
                    </a>
                @endif

            </div>

            {{-- DROPDOWN PROFIL (KANAN) --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full text-sm font-medium text-gray-200 transition focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-cyan-400 flex items-center justify-center text-xs font-bold text-white shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded uppercase">{{ Auth::user()->role }}</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Saya') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="text-red-600" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MOBILE BUTTON --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-2">
            {{-- Jika dia Mitra --}}
            @if(Auth::user()->role === 'mitra')
                <x-responsive-nav-link :href="route('mitra.dashboard')" :active="request()->routeIs('mitra.dashboard')">DASHBOARD MITRA</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mitra.mobil.index')" :active="request()->routeIs('mitra.mobil.*')">ARMADA SAYA</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mitra.pesanan.index')" :active="request()->routeIs('mitra.pesanan.*')">PESANAN MASUK</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">BERANDA</x-responsive-nav-link>
            @endif
            
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.rentals.index')">KELOLA MITRA</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.branches.index')">MASTER WILAYAH</x-responsive-nav-link>
            @endif
        </div>
    </div>
</nav>