<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "Cache berhasil dibersihkan! Silakan refresh halaman web Anda (Ctrl + F5).";
} catch (\Exception $e) {
    echo "Gagal membersihkan cache: " . $e->getMessage();
}
