<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mobils = App\Models\Mobil::all();
echo "All cars:\n";
foreach($mobils as $m) {
    $kota = $m->branch ? $m->branch->kota : "null";
    echo "- " . $m->merk . " " . $m->model . " / " . $kota . "\n";
}
