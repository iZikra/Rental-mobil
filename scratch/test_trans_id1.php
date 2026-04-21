<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trans = App\Models\Transaksi::where('mobil_id', 1)
    ->whereIn(Illuminate\Support\Facades\DB::raw('LOWER(status)'), [
        'pending', 'menunggu', 'menunggu_pembayaran', 'process', 'approved', 'disetujui', 'disewa', 'sedang_jalan', 'sedang_disewa'
    ])->get();

echo "Transactions for Car ID 1:\n";
foreach($trans as $t) {
    echo "- ID Transaksi: " . $t->id . " | Status: " . $t->status . " | Token Expires: " . $t->token_expires_at . "\n";
}
