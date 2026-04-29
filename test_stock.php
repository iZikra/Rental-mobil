<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$mobils = \App\Models\Mobil::with(['branch', 'rental'])->where('status', 'tersedia')->get();
$stockContext = '';
foreach ($mobils as $m) {
    $kota = $m->branch->kota ?? 'Unknown';
    $stockContext .= "- ID: {$m->id} | UNIT: {$m->merk} {$m->model} | KOTA: {$kota} | HARGA: Rp {$m->harga_sewa}/hari | TRANSMISI: {$m->transmisi} | BBM: {$m->bahan_bakar} | KURSI: {$m->jumlah_kursi} | TIPE: {$m->tipe_mobil}\n";
}
echo $stockContext;
