<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$m1 = App\Models\Mobil::find(1);
if ($m1) {
    echo "ID 1 is: " . $m1->merk . " " . $m1->model . " in " . ($m1->branch ? $m1->branch->kota : "unknown") . "\n";
}
