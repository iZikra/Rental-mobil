<nav x-data="{ open: false }" class="bg-white border-b border-gray-300 sticky top-0 z-50 font-serif">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-32">
            
            {{-- LOGO --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="block h-28 w-auto object-contain" />
                </a>
            </div>

            {{-- MENU DESKTOP --}}
            <div class="hidden sm:flex sm:items-center sm:ml-10 sm:space-x-8 flex-1">
                
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                    BERANDA
                </a>

                <a href="{{ route('pages.contact') }}" 
                   class="{{ request()->routeIs('pages.contact') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                    KONTAK
                </a>

                <a href="{{ route('pages.about') }}" 
                   class="{{ request()->routeIs('pages.about') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                    TENTANG KAMI
                </a>

                {{-- MENU USER BIASA --}}
                @if(Auth::user()->role !== 'admin')
                    <a href="{{ route('pages.order') }}" 
                       class="{{ request()->routeIs('pages.order') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                        FORM ORDER
                    </a>

                    {{-- PERBAIKAN: Menggunakan 'riwayat*' agar aktif di semua sub-halaman riwayat --}}
                    <a href="{{ route('riwayat') }}" 
                       class="{{ request()->routeIs('riwayat*') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                        RIWAYAT ORDER
                    </a>
                @endif

                {{-- MENU ADMIN --}}
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('mobils.index') }}" 
                       class="{{ request()->routeIs('mobils.index') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1">
                        {{ __('Kelola Mobil') }}
                    </a>
                    
                    <a href="{{ route('admin.transaksi.index') }}"
                       class="{{ request()->routeIs('admin.transaksi.*') ? 'text-red-600 font-bold border-b-2 border-red-600' : 'text-gray-900 font-medium hover:text-red-600' }} text-base tracking-wide transition duration-150 ease-in-out uppercase py-1 flex items-center">
                        {{ __('Pesanan Masuk') }}
                        
                        {{-- Notifikasi Badge --}}
                        @php $count = \App\Models\Transaksi::where('status', 'pending')->count(); @endphp
                        @if($count > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $count }}</span>
                        @endif
                    </a>
                @endif
            </div>

            {{-- DROPDOWN PROFIL (KANAN) --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-sans font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-sans">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="font-sans"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- TOMBOL HAMBURGER (MOBILE) --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE (RESPONSIVE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden font-sans">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">BERANDA</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pages.contact')" :active="request()->routeIs('pages.contact')">KONTAK</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pages.about')" :active="request()->routeIs('pages.about')">TENTANG KAMI</x-responsive-nav-link>
            
            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('pages.order')" :active="request()->routeIs('pages.order')">FORM ORDER</x-responsive-nav-link>
                {{-- PERBAIKAN: Menambahkan Riwayat Order di Mobile --}}
                <x-responsive-nav-link :href="route('riwayat')" :active="request()->routeIs('riwayat*')">RIWAYAT ORDER</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role == 'admin')
                <x-responsive-nav-link :href="route('mobils.index')" :active="request()->routeIs('mobils.*')">KELOLA MOBIL</x-responsive-nav-link>
                {{-- PERBAIKAN: Menambahkan Pesanan Masuk di Mobile --}}
                <x-responsive-nav-link :href="route('admin.transaksi.index')" :active="request()->routeIs('admin.transaksi.*')">PESANAN MASUK</x-responsive-nav-link>
            @endif
        </div>

        {{-- PROFIL MOBILE --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>