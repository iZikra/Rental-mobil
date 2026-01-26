<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{'bg-slate-900/80 backdrop-blur-xl border-white/10': scrolled, 'bg-slate-900 border-transparent': !scrolled}"
     class="sticky top-0 z-50 border-b transition-all duration-300 font-sans">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24 items-center">
            
            {{-- LOGO --}}
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="group">
                    {{-- Logo Filter: Menambahkan brightness agar logo terlihat jelas di background gelap --}}
                    <img src="{{ asset('img/logo1.png') }}" alt="Logo" 
                         class="block h-16 w-auto object-contain transition transform group-hover:scale-110 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]" />
                </a>
                <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-white tracking-tighter">FZ<span class="text-blue-500">RENT</span></h1>
                </div>
            </div>

            {{-- MENU DESKTOP --}}
            <div class="hidden sm:flex sm:items-center sm:ml-10 sm:space-x-8">
                
                @php
                    $navClass = "text-sm font-bold tracking-widest uppercase transition duration-300 py-2 border-b-2";
                    $activeClass = "text-blue-400 border-blue-400 drop-shadow-[0_0_8px_rgba(59,130,246,0.5)]";
                    $inactiveClass = "text-gray-400 border-transparent hover:text-white hover:border-gray-500";
                @endphp

                <a href="{{ route('dashboard') }}" class="{{ $navClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                    Beranda
                </a>

                <a href="{{ route('pages.contact') }}" class="{{ $navClass }} {{ request()->routeIs('pages.contact') ? $activeClass : $inactiveClass }}">
                    Kontak
                </a>

                <a href="{{ route('pages.about') }}" class="{{ $navClass }} {{ request()->routeIs('pages.about') ? $activeClass : $inactiveClass }}">
                    Tentang Kami
                </a>

                {{-- MENU USER BIASA --}}
                @if(Auth::user()->role !== 'admin')
                    <a href="{{ route('pages.order') }}" class="{{ $navClass }} {{ request()->routeIs('pages.order') ? $activeClass : $inactiveClass }}">
                        Booking
                    </a>

                    <a href="{{ route('riwayat') }}" class="{{ $navClass }} {{ request()->routeIs('riwayat*') ? $activeClass : $inactiveClass }}">
                        Riwayat
                    </a>
                @endif

                {{-- MENU ADMIN --}}
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('mobils.index') }}" class="{{ $navClass }} {{ request()->routeIs('mobils.index') ? $activeClass : $inactiveClass }}">
                        Armada
                    </a>
                    
                    <a href="{{ route('admin.transaksi.index') }}"
                       class="{{ $navClass }} {{ request()->routeIs('admin.transaksi.*') ? $activeClass : $inactiveClass }} flex items-center gap-2">
                        Pesanan
                        @php $count = \App\Models\Transaksi::where('status', 'pending')->count(); @endphp
                        @if($count > 0)
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white animate-pulse">
                                {{ $count }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>

            {{-- DROPDOWN PROFIL (KANAN) --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full text-sm font-medium text-gray-200 transition focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-cyan-400 flex items-center justify-center text-xs font-bold text-white shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white rounded-md p-1">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-gray-100 rounded-md">
                                <i class="fa-regular fa-user mr-2"></i> {{ __('Profile Saya') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50 rounded-md"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- TOMBOL HAMBURGER (MOBILE) --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE (RESPONSIVE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:bg-white/5">BERANDA</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pages.contact')" :active="request()->routeIs('pages.contact')" class="text-gray-300 hover:text-white hover:bg-white/5">KONTAK</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pages.about')" :active="request()->routeIs('pages.about')" class="text-gray-300 hover:text-white hover:bg-white/5">TENTANG KAMI</x-responsive-nav-link>
            
            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('pages.order')" :active="request()->routeIs('pages.order')" class="text-gray-300 hover:text-white hover:bg-white/5">BOOKING</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('riwayat')" :active="request()->routeIs('riwayat*')" class="text-gray-300 hover:text-white hover:bg-white/5">RIWAYAT</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role == 'admin')
                <div class="border-t border-white/10 my-2 pt-2">
                    <p class="px-4 text-xs font-bold text-gray-500 uppercase">Menu Admin</p>
                    <x-responsive-nav-link :href="route('mobils.index')" :active="request()->routeIs('mobils.*')" class="text-gray-300 hover:text-white hover:bg-white/5">KELOLA ARMADA</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.transaksi.index')" :active="request()->routeIs('admin.transaksi.*')" class="text-gray-300 hover:text-white hover:bg-white/5">PESANAN MASUK</x-responsive-nav-link>
                </div>
            @endif
        </div>

        {{-- PROFIL MOBILE --}}
        <div class="pt-4 pb-4 border-t border-white/10 bg-black/20">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-4 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">{{ __('Profile Saya') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>