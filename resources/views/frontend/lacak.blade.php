<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan Anda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    {{-- Header Sederhana --}}
    <nav class="bg-white shadow-md mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="{{ route('homepage') }}" class="text-3xl font-bold text-gray-900">DriveNow Rental Mobil</a>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Lacak Pesanan Anda</h1>

            {{-- FORM PELACAKAN --}}
            <form action="{{ route('booking.lacak.submit') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="kode_booking" class="block text-sm font-medium text-gray-700">Kode Booking</label>
                        <input type="text" name="kode_booking" id="kode_booking" value="{{ old('kode_booking', $reservation->kode_booking ?? '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Pemesan</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $reservation->customer->email ?? '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    @error('kode_booking')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-6 text-right">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700">
                        Lacak Pesanan
                    </button>
                </div>
            </form>

            {{-- TAMPILKAN HASIL JIKA DITEMUKAN --}}
            @isset($reservation)
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Detail Pesanan Ditemukan</h2>

                    <div class="space-y-2">
                        <p><strong>Mobil:</strong> {{ $reservation->car->nama_mobil }}</p>
                        <p><strong>Pelanggan:</strong> {{ $reservation->customer->nama_customer }}</p>
                        <p><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($reservation->tanggal_mulai)->format('d M Y, \p\u\k\u\l H:i') }}</p>
                        <p><strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($reservation->tanggal_selesai)->format('d M Y, \p\u\k\u\l H:i') }}</p>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($reservation->total_harga) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($reservation->status == 'menunggu') bg-yellow-100 text-yellow-800 @endif
                                @if ($reservation->status == 'dikonfirmasi') bg-green-100 text-green-800 @endif
                                @if ($reservation->status == 'selesai') bg-gray-100 text-gray-800 @endif
                                @if ($reservation->status == 'dibatalkan') bg-red-100 text-red-800 @endif
                            ">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            @endisset

        </div>
    </div>
</body>
</html>