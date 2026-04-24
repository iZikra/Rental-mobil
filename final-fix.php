<?php
// Aktifkan laporan error secara paksa
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
        require $maintenance;
    }

    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';

    $app->handleRequest(Request::capture());
} catch (\Exception $e) {
    echo "<h3>Laravel Fatal Error:</h3>";
    echo "<b>Message:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . "<br>";
    echo "<b>Line:</b> " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
