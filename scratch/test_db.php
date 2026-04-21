<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mobils = App\Models\Mobil::with('branch')->get();
$pekanbaru_cars = [];
foreach($mobils as $m) {
    if ($m->branch && $m->branch->kota == 'Pekanbaru') {
        $pekanbaru_cars[] = $m->merk . ' ' . $m->model . ' (' . $m->status . ') (Rental ID: ' . $m->rental_id . ')';
    }
}
echo "Cars in Pekanbaru:\n";
echo implode("\n", $pekanbaru_cars) . "\n";
