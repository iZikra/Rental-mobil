<?php
echo "<h3>Laravel Debug Check</h3>";

$paths = [
    'Vendor Autoload' => 'vendor/autoload.php',
    'Bootstrap App' => 'bootstrap/app.php',
    'Public Index' => 'public/index.php',
    '.env File' => '.env',
];

foreach ($paths as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name found at: $path <br>";
    } else {
        echo "❌ $name NOT found at: $path <br>";
    }
}

echo "<hr>";

if (file_exists('vendor/autoload.php')) {
    try {
        require 'vendor/autoload.php';
        echo "✅ vendor/autoload.php loaded successfully! <br>";
    } catch (Exception $e) {
        echo "❌ Error loading vendor/autoload.php: " . $e->getMessage() . "<br>";
    }
}

if (file_exists('.env')) {
    $env = file_get_contents('.env');
    if (strpos($env, 'APP_KEY=') !== false) {
        echo "✅ APP_KEY found in .env <br>";
    } else {
        echo "❌ APP_KEY NOT found in .env <br>";
    }
} else {
    echo "❌ Cannot check APP_KEY because .env is missing. <br>";
}
?>
