<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reply = "1. Daihatsu Xenia Rp 300.000/hari (Mitra: FZ RENT CAR) [LINK_BOOKING:1|TANGGAL]";

if (preg_match_all('/\[LINK_BOOKING:(\d+)\|([^\]]+)\]/', $reply, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
        $fullTag = $match[0];
        $carId = $match[1];
        $car = App\Models\Mobil::find($carId);
        $token = "abcd-1234";
        $uniqueLink = url('/rental/' . $car->rental_id . '/guest-booking/' . $token);
        $htmlLink = '<a href="' . $uniqueLink . '" class="text-blue-600 font-bold underline hover:text-blue-800" target="_blank">Klik Disini untuk Booking</a>';
        $reply = str_replace($fullTag, $htmlLink, $reply);
    }
}
echo "Parsed Reply:\n$reply\n";
