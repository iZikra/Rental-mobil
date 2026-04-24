<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-900">
            Laporan Keuangan
        </h2>
        
        <div class="flex flex-col sm:flex-row items-center gap-2">
            
            <form action="<?php echo e(route('reports.index')); ?>" method="GET" class="flex items-center space-x-2">
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="border-gray-300 rounded-md shadow-sm text-sm">
                <span class="text-gray-500">sampai</span>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="border-gray-300 rounded-md shadow-sm text-sm">
                <input type="hidden" name="periode" value="<?php echo e(request('periode', 'bulan')); ?>"> 
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700">
                    Filter Tanggal
                </button>
            </form>

            
            <form action="<?php echo e(route('reports.index')); ?>" method="GET" class="flex items-center space-x-2 ml-4"> 
                <select name="periode" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="hari" <?php echo e($periode == 'hari' ? 'selected' : ''); ?>>Harian</option>
                    <option value="bulan" <?php echo e($periode == 'bulan' ? 'selected' : ''); ?>>Bulanan</option>
                    <option value="tahun" <?php echo e($periode == 'tahun' ? 'selected' : ''); ?>>Tahunan</option>
                </select>
                <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>"> 
                <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>"> 
            </form>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Total Pendapatan</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                Rp<?php echo e(number_format($totalPendapatan)); ?>

            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-700">Transaksi Selesai</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                <?php echo e($jumlahTransaksi); ?>

            </p>
        </div>
    </div>

    
    
    
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">
            <?php echo e($chartTitle); ?>

        </h3>
        <div>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    
    
    
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
                    <?php $__empty_1 = true; $__currentLoopData = $profitPerCar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($report->car->nama_mobil ?? 'Mobil Dihapus'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($report->car->merek ?? ''); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($report->total_bookings); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp<?php echo e(number_format($report->total_profit)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Tidak ada data.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
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
                <?php $__empty_1 = true; $__currentLoopData = $completedReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($reservation->kode_booking); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($reservation->customer->nama_customer); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($reservation->car->nama_mobil); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(\Carbon\Carbon::parse($reservation->tanggal_selesai)->format('d M Y')); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp<?php echo e(number_format($reservation->total_harga)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada data transaksi yang selesai.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = <?php echo $chartLabels->toJson(); ?>;
            const values = <?php echo $chartValues->toJson(); ?>; // Menggunakan $chartValues
            const chartTitle = "<?php echo e($chartTitle); ?>"; // Menggunakan $chartTitle

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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>