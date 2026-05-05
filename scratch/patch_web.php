<?php
$content = file_get_contents('routes/web.php');

$newRoute = "
// Proxy image server untuk hosting gratisan yang memblokir akses ke /storage
Route::get('/img-proxy/{path}', function (\$path) {
    \$path = str_replace(['img-proxy//', 'img-proxy/'], ['', ''], \$path);
    
    // Semua kemungkinan lokasi fisik file di hosting InfinityFree
    \$possiblePaths = [
        storage_path('app/public/' . \$path),
        public_path('storage/' . \$path),
        base_path('public/storage/' . \$path),
        base_path('storage/' . \$path),
        __DIR__ . '/../public/storage/' . \$path,
        __DIR__ . '/../storage/app/public/' . \$path
    ];

    foreach (\$possiblePaths as \$p) {
        if (file_exists(\$p)) {
            return response()->file(\$p);
        }
    }

    // Coba tambahkan/hilangkan mobil_images/
    if (strpos(\$path, 'mobil_images/') !== false) {
        \$cleanPath = str_replace('mobil_images/', '', \$path);
        \$possiblePathsClean = [
            storage_path('app/public/mobil_images/' . \$cleanPath),
            public_path('storage/mobil_images/' . \$cleanPath),
            base_path('public/storage/mobil_images/' . \$cleanPath),
            __DIR__ . '/../public/storage/mobil_images/' . \$cleanPath
        ];
        foreach (\$possiblePathsClean as \$p) {
            if (file_exists(\$p)) {
                return response()->file(\$p);
            }
        }
    }

    abort(404, \"File not found: \" . \$path);
})->where('path', '.*');
";

// replace the old route
$content = preg_replace('/Route::get\(\'\/img-proxy\/{path}\', function \(\$path\) \{.*?\n\}\)->where\(\'path\', \'\.\*\'\);/s', trim($newRoute), $content);

file_put_contents('routes/web.php', $content);
echo "web.php patched.";
