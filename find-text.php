<?php
echo "<h3>Mencari sumber teks 'OK LARAVEL HIDUP'...</h3>";

function searchInFiles($dir, $pattern) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            // Kita batasi hanya di folder inti untuk kecepatan
            if (in_array($file, ['app', 'bootstrap', 'config', 'routes', 'public'])) {
                searchInFiles($path, $pattern);
            }
        } else {
            if (strpos($file, '.php') !== false) {
                $content = file_get_contents($path);
                if (strpos($content, $pattern) !== false) {
                    echo "🎯 <b>DITEMUKAN DI:</b> $path <br>";
                }
            }
        }
    }
}

searchInFiles('./', 'OK LARAVEL HIDUP');
echo "<hr>Pencarian selesai.";
?>
