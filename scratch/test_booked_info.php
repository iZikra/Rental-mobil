<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trans = App\Models\Transaksi::with('mobil.branch')->whereIn('mobil_id', [1, 2])->get();
foreach($trans as $t) {
    if (!$t->mobil) continue;
    $kota = $t->mobil->branch ? $t->mobil->branch->kota : 'unknown';
    echo "ID Mobil Transaksi: {$t->mobil_id} | Mobil: {$t->mobil->merk} {$t->mobil->model} ({$kota}) | Status: {$t->status} | Token Exp: " . ($t->token_expires_at ?? 'NULL') . "\n";
}
