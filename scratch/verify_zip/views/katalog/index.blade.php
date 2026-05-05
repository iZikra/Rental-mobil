<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Sewa Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .car-card {
            animation: fadeInUp 0.45s ease-out forwards;
            opacity: 0;
        }
        .car-img { transition: transform 0.45s cubic-bezier(.4,0,.2,1); }
        .car-card:hover .car-img { transform: scale(1.06); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

{{-- HEADER --}}
<div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 py-12 px-4 mb-10 shadow-2xl">
    <div class="max-w-6xl mx-auto flex flex-col items-center text-center">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center mb-4">
            <i class="fa-solid fa-car text-blue-400 text-xl"></i>
        </div>
        <h1 class="text-4xl font-black text-white tracking-tight mb-2">Katalog Armada</h1>
        <p class="text-slate-400 text-base">Temukan kendaraan impian Anda dari mitra rental terpercaya</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 pb-16">

    {{-- FILTER BAR --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm px-6 py-4 mb-8 flex flex-col md:flex-row items-center gap-4">
        <div class="flex items-center gap-2 text-gray-700 font-bold text-sm shrink-0">
            <i class="fa-solid fa-sliders text-blue-500"></i>
            Filter Lokasi
        </div>
        <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-1 gap-3 w-full">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-location-dot text-blue-400"></i>
                </div>
                <select name="kota" id="kota"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-800 font-semibold text-sm focus:ring-2 focus:ring-blue-500 cursor-pointer"
                        onchange="this.form.submit()">
                    <option value="">Semua Kota</option>
                    @foreach($daftarKota as $kota)
                        <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                    @endforeach
                </select>
            </div>
            @if(request('kota'))
            <a href="{{ route('katalog.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 border border-red-100 text-red-600 font-bold text-sm hover:bg-red-100 transition shrink-0">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
            @endif
        </form>
        <div class="text-sm text-gray-500 shrink-0">
            <span class="font-bold text-blue-600">{{ $mobils->total() }}</span> unit tersedia
        </div>
    </div>

    {{-- GRID KARTU MOBIL --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mobils as $index => $mobil)

        {{-- KARTU MOBIL --}}
        <div class="car-card bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
             style="animation-delay: {{ $index * 60 }}ms">

            {{-- AREA GAMBAR: background biru muda, gambar object-contain --}}
            <div class="relative m-3 rounded-xl overflow-hidden h-48"
                 style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);">

                <img src="{{ $mobil->image_url }}"
                     alt="{{ $mobil->merk }} {{ $mobil->model }}"
                     class="car-img w-full h-full object-contain p-3"
                     onerror="this.src='https://placehold.co/600x400/dbeafe/3b82f6?text=Mobil'">

                {{-- BADGE TERSEDIA - pojok kanan atas --}}
                <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                    Tersedia
                </span>
            </div>

            {{-- KONTEN BAWAH --}}
            <div class="px-4 pb-4 pt-1">

                {{-- MERK: huruf kapital biru --}}
                <p class="text-blue-600 font-extrabold text-xs uppercase tracking-widest mb-0.5">
                    {{ $mobil->merk }}
                </p>

                {{-- NAMA MOBIL --}}
                <h3 class="text-xl font-black text-gray-900 mb-3 leading-tight">
                    {{ $mobil->merk }} {{ $mobil->model }}
                </h3>

                {{-- BOX LOKASI & MITRA --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5 mb-3 space-y-1.5">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-location-dot text-red-500 w-4 text-center shrink-0"></i>
                        <span class="font-semibold text-red-500">Lokasi: {{ $mobil->branch->kota ?? '-' }}</span>
                    </div>
                    <div class="flex items-start gap-2 text-sm">
                        <i class="fa-solid fa-building text-gray-500 w-4 text-center shrink-0 mt-0.5"></i>
                        <span class="font-semibold text-gray-700 leading-tight">Mitra: {{ $mobil->rental->nama_rental ?? '-' }}</span>
                    </div>
                </div>

                {{-- SPESIFIKASI: Kursi + Transmisi --}}
                <div class="flex items-center gap-6 text-gray-500 text-sm mb-4">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-chair text-blue-300"></i>
                        {{ $mobil->jumlah_kursi }} Kursi
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-gears text-blue-300"></i>
                        {{ $mobil->transmisi }}
                    </span>
                </div>

                {{-- HARGA + TOMBOL BULAT --}}
                <div class="border-t border-gray-100 pt-3">
                    <p class="text-[10px] font-extrabold tracking-widest uppercase text-gray-400 mb-0.5">Harga Sewa</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black text-gray-900">
                            Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('pages.order', ['mobil_id' => $mobil->id]) }}"
                           title="Sewa Sekarang"
                           class="w-11 h-11 bg-slate-900 hover:bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md transition-colors duration-300">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        @empty
        <div class="col-span-3 bg-white border border-dashed border-gray-300 rounded-2xl text-center py-16 px-8">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-car-burst text-3xl text-blue-200"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-1">Tidak Ada Unit Tersedia</h3>
            <p class="text-gray-500 text-sm">Tidak ada unit tersedia di kota <strong>"{{ request('kota') }}"</strong>.</p>
            <a href="{{ route('katalog.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition text-sm">
                Lihat Semua Kota
            </a>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($mobils->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $mobils->links() }}
    </div>
    @endif

</div>
</body>
</html>