<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = App\Models\Transaksi::latest()->first();
echo json_encode([
    'token' => $t->booking_token, 
    'rental_id' => $t->rental_id,
    'expires' => $t->token_expires_at, 
    'now' => now()->toDateTimeString(), 
    'diff' => $t->token_expires_at > now()
]);
