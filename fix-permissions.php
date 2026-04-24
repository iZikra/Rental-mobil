<?php
echo "<h3>Memperbaiki Folder Storage & Cache...</h3>";

$dirs = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "✅ Folder dibuat: $dir <br>";
        } else {
            echo "❌ Gagal membuat folder: $dir <br>";
        }
    } else {
        chmod($dir, 0777);
        echo "✅ Izin folder diperbarui: $dir <br>";
    }
}

// Hapus file cache lama jika ada
$cacheFiles = ['bootstrap/cache/config.php', 'bootstrap/cache/services.php', 'bootstrap/cache/packages.php', 'bootstrap/cache/routes.php'];
foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "🗑️ File cache dihapus: $file <br>";
    }
}

echo "<hr>Selesai. Silakan coba buka kembali website Anda.";
?>
