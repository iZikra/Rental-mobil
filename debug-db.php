<?php
echo "<h3>Database Connection Debug</h3>";

if (!file_exists('.env')) {
    die("❌ .env file not found!");
}

$env = file_get_contents('.env');
$lines = explode("\n", $env);
$config = [];

foreach ($lines as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        list($key, $value) = explode('=', $line, 2);
        $config[trim($key)] = trim($value);
    }
}

$host = $config['DB_HOST'] ?? '';
$db   = $config['DB_DATABASE'] ?? '';
$user = $config['DB_USERNAME'] ?? '';
$pass = $config['DB_PASSWORD'] ?? '';

echo "Attempting to connect to: <br>";
echo "Host: $host <br>";
echo "Database: $db <br>";
echo "User: $user <br>";

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    echo "✅ <b>Success!</b> Database connected successfully. <br>";
} catch (PDOException $e) {
    echo "❌ <b>Database Connection Failed:</b> " . $e->getMessage() . "<br>";
    
    if ($host == 'localhost' || $host == '127.0.0.1') {
        echo "<p style='color:red'><b>TIP:</b> Di InfinityFree, Anda TIDAK BISA menggunakan 'localhost'. Cek di Panel Hosting (MySQL Databases) untuk mendapatkan 'MySQL Hostname' Anda.</p>";
    }
}
?>
