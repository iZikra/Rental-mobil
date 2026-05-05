<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Edit Pemesanan #{{ $reservation->id }}
        </h2>
        <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Detail Pemesanan</h3>
            <p class="text-gray-600">Pelanggan: {{ $reservation->customer->nama_customer }}</p>
            <p class="text-gray-600">Mobil: {{ $reservation->car->nama_mobil }}</p>
            <p class="text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($reservation->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($reservation->tanggal_selesai)->format('d M Y') }}</p>
        </div>

        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                <select name="status" id="status" required class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="menunggu" {{ $reservation->status == 'menunggu' ? 'selected' : '' }}>
                        Menunggu
                    </option>
                    <option value="dikonfirmasi" {{ $reservation->status == 'dikonfirmasi' ? 'selected' : '' }}>
                        Dikonfirmasi
                    </option>
                    <option value="selesai" {{ $reservation->status == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="dibatalkan" {{ $reservation->status == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="mt-8 text-left">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
    </div>
</x-app-layout>