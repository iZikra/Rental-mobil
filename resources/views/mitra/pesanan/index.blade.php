<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Manajemen Pesanan Mitra') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-sm font-medium text-gray-500">Live Updates</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-500 text-white font-bold rounded shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-500 text-white font-bold rounded shadow-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-900 text-white">
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest rounded-tl-xl">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Unit Mobil</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Dokumen</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Total Harga</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest">Status Saat Ini</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-widest rounded-tr-xl">Aksi Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($pesanan as $p)
                                <tr class="hover:bg-blue-50/50 transition duration-200">
                                    {{-- Kolom Pelanggan --}}
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold uppercase">
                                                {{ substr($p->nama ?? ($p->user->name ?? 'U'), 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-black text-gray-900">{{ $p->nama ?? ($p->user->name ?? 'No Name') }}</div>
                                                <div class="text-[11px] text-blue-600 font-medium">{{ $p->no_hp ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Unit Mobil --}}
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($p->mobil)
                                            <div class="text-sm font-bold text-gray-900 uppercase tracking-tight">
                                                {{ $p->mobil->merk ?? 'UNIT UNKNOWN' }}
                                            </div>
                                            <div class="flex flex-col gap-1 mt-2">
                                                <div class="flex items-center gap-1.5 text-[10px] text-gray-500 font-medium">
                                                    <i class="fa-solid fa-location-dot text-blue-500"></i>
                                                    <span class="truncate max-w-[150px]" title="{{ $p->alamat_jemput ?? 'Ambil di Kantor' }}">
                                                        {{ $p->alamat_jemput ?? 'Ambil di Kantor' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">-</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Dokumen --}}
<td class="px-6 py-5 whitespace-nowrap text-center">
    <div class="flex flex-col items-center gap-3">
        
        {{-- Container KTP dan SIM sejajar --}}
        <div class="flex items-center gap-3 justify-center">
            {{-- FOTO KTP --}}
            @if($p->foto_identitas)
                <a href="{{ asset('storage/' . $p->foto_identitas) }}" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                    <img src="{{ asset('storage/' . $p->foto_identitas) }}" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-blue-500 shadow-sm" alt="KTP">
                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow">KTP</span>
                </a>
            @endif

            {{-- FOTO SIM (TAMBAHAN BARU) --}}
            @if($p->foto_sim)
                <a href="{{ asset('storage/' . $p->foto_sim) }}" target="_blank" class="group relative transform hover:scale-110 transition duration-200">
                    <img src="{{ asset('storage/' . $p->foto_sim) }}" class="h-10 w-14 object-cover rounded border border-gray-300 group-hover:border-emerald-500 shadow-sm" alt="SIM">
                    <span class="absolute -top-2 -right-2 bg-emerald-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow">SIM</span>
                </a>
            @endif
        </div>

    </div>
</td>

                                    {{-- Kolom Harga --}}
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="text-sm font-black text-gray-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase">{{ $p->lama_sewa ?? 0 }} Hari</div>
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        @php
                                            $stRaw = strtolower(trim($p->status));
                                            $color = match($stRaw) {
                                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'disewa' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'selesai' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                'dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-[10px] font-black rounded-full border shadow-sm {{ $color }} uppercase">
                                            {{ $p->status }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi Konfirmasi --}}
                                    
<td class="px-6 py-5 whitespace-nowrap text-center">
    <div class="flex flex-col items-center justify-center gap-2">
        
        @php $stRaw = strtolower(trim($p->status)); @endphp

        @if($stRaw == 'pending')
            <span class="text-gray-400 text-[10px] font-bold italic uppercase">Menunggu pembayaran</span>
            <form action="{{ route('mitra.pesanan.tolak', $p->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Tolak pesanan ini?')" class="w-full bg-white border-2 border-rose-500 text-rose-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                    Tolak
                </button>
            </form>

        @elseif($stRaw == 'disewa')
            <form action="{{ route('mitra.pesanan.selesai', $p->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-lg transition-all">
                    Selesai Sewa
                </button>
            </form>
        @else
            <span class="text-gray-300 text-[10px] font-bold italic uppercase">Status: {{ $stRaw }}</span>
        @endif
    </div>
</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center text-gray-400 font-bold uppercase tracking-widest">
                                        Belum Ada Data Pesanan Masuk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
