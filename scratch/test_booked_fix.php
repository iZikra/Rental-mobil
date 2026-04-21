<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booked = App\Models\Transaksi::whereIn(Illuminate\Support\Facades\DB::raw('LOWER(status)'), [
    'pending', 'menunggu', 'menunggu_pembayaran', 'process', 'approved', 'disetujui', 'disewa', 'sedang_jalan', 'sedang_disewa'
])
->where(function ($query) {
    // Hanya hitung yang bukan draft kadaluwarsa
    $query->whereNull('token_expires_at')
          ->orWhere('token_expires_at', '>', now());
})
->pluck('mobil_id')->toArray();

echo "Booked car IDs: " . implode(", ", $booked) . "\n";
