<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mobils = \App\Models\Mobil::all();
foreach ($mobils as $m) {
    echo "ID: " . $m->id . "\n";
    echo "Merk: " . $m->merk . "\n";
    echo "Model: " . $m->model . "\n";
    echo "image_url: " . var_export($m->image_url, true) . "\n";
    echo "gambarMobil: " . \gambarMobil($m->model) . "\n";
    echo "asset: " . asset(\gambarMobil($m->model)) . "\n";
    echo "--------------------------\n";
}
