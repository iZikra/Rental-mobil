<x-app-layout>
    {{-- JUDUL HALAMAN DAN FILTER TANGGAL --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-900">
            Laporan Keuangan
        </h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-2">
            {{-- Form Filter Tanggal --}}
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-gray-300 rounded-md shadow-sm text-sm">
                <span class="text-gray-500">sampai</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-gray-300 rounded-md shadow-sm text-sm">
                <input type="hidden" name="periode" value="{{ request('periode', 'bulan') }}"> {{-- Pertahankan filter periode --}}
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700">
                    Filter Tanggal
                </button>
            </form>

            {{-- Form Filter Periode Grafik --}}
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center space-x-2 ml-4"> {{-- Margin-left untuk pemisah --}}
                <select name="periode" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="hari" {{ $periode == 'hari' ? 'selected' : '' }}>Harian</option>
                    <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahun" {{ $periode == 'tahun' ? 'selected' : '' }}>Tahunan</option>
                </select>
                <input type="hidden" name="start_date" value="{{ request('start_date') }}"> {{-- Pertahankan filter tanggal --}}
                <input type="hidden" name="end_date" value="{{ request('end_date') }}"> {{-- Pertahankan filter tanggal --}}
            </form>
        </div>
    </div>

    {{-- KARTU RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Total Pendapatan</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                Rp{{ number_format($totalPendapatan) }}
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Transaksi Selesai</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $jumlahTransaksi }}
            </p>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- == IDE 1: TREN PENDAPATAN (GRAFIK) --}}
    {{-- ========================================================== --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
            {{ $chartTitle }}
        </h3>
        <div>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- == IDE 2: PROFITABILITAS PER MOBIL (TABEL BARU) --}}
    {{-- ========================================================== --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
            Laporan Profitabilitas per Mobil
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mobil
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Booking Selesai
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pendapatan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($profitPerCar as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $report->car->nama_mobil ?? 'Mobil Dihapus' }}</div>
                                <div class="text-sm text-gray-500">{{ $report->car->merek ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->total_bookings }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp{{ number_format($report->total_profit) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Tidak ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL DETAIL TRANSAKSI SELESAI --}}
    <h3 class="text-xl font-bold text-gray-900 mb-4">
        Detail Transaksi Selesai
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Booking</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($completedReservations as $reservation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reservation->kode_booking }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reservation->customer->nama_customer }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $reservation->car->nama_mobil }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->tanggal_selesai)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp{{ number_format($reservation->total_harga) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada data transaksi yang selesai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- SCRIPT UNTUK CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = {!! $chartLabels->toJson() !!};
            const values = {!! $chartValues->toJson() !!}; // Menggunakan $chartValues
            const chartTitle = "{{ $chartTitle }}"; // Menggunakan $chartTitle

            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: chartTitle, // Gunakan chartTitle di sini
                        data: values, // Gunakan values di sini
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: chartTitle // Judul grafik di dalam options
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>