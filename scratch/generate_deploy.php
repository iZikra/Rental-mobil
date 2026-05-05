<?php
$webPhp = file_get_contents(__DIR__ . '/../routes/web.php');
$mobilPhp = file_get_contents(__DIR__ . '/../app/Models/Mobil.php');

$webPhpBase64 = base64_encode($webPhp);
$mobilPhpBase64 = base64_encode($mobilPhp);

$deployScript = "<?php
// AUTO-PATCH SCRIPT (Tanpa butuh file ZIP)
echo \"<h3>⚙️ Memulai Proses Patching Otomatis...</h3>\";

// 1. Patch routes/web.php
\$webPhpBase64 = \"$webPhpBase64\";

\$webPhpPath = __DIR__ . '/routes/web.php';
file_put_contents(\$webPhpPath, base64_decode(\$webPhpBase64));
echo \"✅ Berhasil menimpa <b>routes/web.php</b><br>\";

// 2. Patch app/Models/Mobil.php
\$mobilPhpBase64 = \"$mobilPhpBase64\";

\$mobilPhpPath = __DIR__ . '/app/Models/Mobil.php';
if(file_exists(dirname(\$mobilPhpPath))) {
    file_put_contents(\$mobilPhpPath, base64_decode(\$mobilPhpBase64));
    echo \"✅ Berhasil menimpa <b>app/Models/Mobil.php</b><br>\";
} else {
    echo \"⚠️ Folder app/Models tidak ditemukan. Lewati Mobil.php.<br>\";
}

echo \"<hr>\";

// 3. Hapus Cache View Manual
\$viewCachePath = __DIR__ . '/storage/framework/views';
if (is_dir(\$viewCachePath)) {
    \$files = glob(\$viewCachePath . '/*');
    \$deletedCount = 0;
    foreach (\$files as \$f) {
        if (is_file(\$f) && basename(\$f) !== '.gitignore') {
            unlink(\$f);
            \$deletedCount++;
        }
    }
    echo \"✅ Berhasil menghapus <b>\$deletedCount</b> file cache tampilan (views).<br>\";
}

echo \"<hr>\";

// 4. Clear Cache Laravel
try {
    require __DIR__.'/vendor/autoload.php';
    \$app = require_once __DIR__.'/bootstrap/app.php';
    \$kernel = \$app->make(Illuminate\Contracts\Http\Kernel::class);
    \$response = \$kernel->handle(
        \$request = Illuminate\Http\Request::capture()
    );
    
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo \"✅ Cache sistem Laravel berhasil dibersihkan dengan Artisan.<br>\";
} catch (\\Exception \$e) {
    echo \"⚠️ Gagal menjalankan Artisan: \" . \$e->getMessage() . \"<br>\";
}

echo \"<hr>\";
echo \"<h3>✨ SEMUA PERBAIKAN SELESAI DITERAPKAN! ✨</h3>\";
echo \"<p>Tidak perlu file ZIP lagi. Sistem sudah langsung diperbaiki dari dalam.</p>\";
echo \"<p>Silakan buka website Anda di Mode Samaran dan tekan <b>Ctrl + F5</b>.</p>\";
";

file_put_contents(__DIR__ . '/../deploy_frontend.php', $deployScript);
echo "deploy_frontend.php updated successfully.";
