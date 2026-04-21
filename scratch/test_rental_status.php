<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mobils = App\Models\Mobil::with(['branch', 'rental'])->get();
foreach($mobils as $m) {
    if ($m->branch && $m->branch->kota == 'Pekanbaru') {
        $rental_status = $m->rental ? $m->rental->status : 'no rental';
        echo $m->merk . ' ' . $m->model . ' - Rental Status: ' . $rental_status . " - ID: " . $m->rental_id . "\n";
    }
}
