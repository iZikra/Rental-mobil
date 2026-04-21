<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booked = App\Models\Transaksi::whereIn(Illuminate\Support\Facades\DB::raw('LOWER(status)'), [
    'pending', 'menunggu', 'menunggu_pembayaran', 'process', 'approved', 'disetujui', 'disewa', 'sedang_jalan', 'sedang_disewa'
])->pluck('mobil_id')->toArray();

echo "Booked car IDs: " . implode(", ", $booked) . "\n";
