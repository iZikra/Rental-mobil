<?php
// 1. Ekstrak File ZIP
$file = 'update_frontend_final.zip';
if (file_exists($file)) {
    $zip = new ZipArchive;
    if ($zip->open($file) === TRUE) {
        $zip->extractTo(__DIR__); // Ekstrak ke folder saat ini (htdocs)
        $zip->close();
        echo "✅ Berhasil mengekstrak file: <b>$file</b> <br>";
    } else {
        echo "❌ Gagal membuka file zip: $file <br>";
    }
} else {
    echo "⚠️ File <b>$file</b> tidak ditemukan! Pastikan Anda sudah mengunggahnya ke folder yang sama dengan file ini.<br>";
}

echo "<hr>";

// 2. Hapus Cache View Manual
$viewCachePath = __DIR__ . '/storage/framework/views';
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . '/*');
    $deletedCount = 0;
    foreach ($files as $f) {
        if (is_file($f) && basename($f) !== '.gitignore') {
            unlink($f);
            $deletedCount++;
        }
    }
    echo "✅ Berhasil menghapus <b>$deletedCount</b> file cache tampilan (views).<br>";
} else {
    echo "ℹ️ Folder cache view tidak ditemukan, mungkin sudah bersih.<br>";
}

echo "<hr>";

// 3. Clear Cache Laravel menggunakan Artisan (Jika memungkinkan)
try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "✅ Cache sistem Laravel berhasil dibersihkan dengan Artisan.<br>";
} catch (\Exception $e) {
    echo "⚠️ Gagal menjalankan Artisan clear cache (tidak masalah jika penghapusan manual di atas berhasil): " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>TUGAS SELESAI!</h3>";
echo "<p>Silakan buka kembali website Anda dan tekan <b>Ctrl + F5</b> di keyboard untuk melihat perubahan.</p>";
