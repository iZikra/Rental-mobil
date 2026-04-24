<?php
echo "<h3>Isi Folder 'laravel_app'</h3>";
if (is_dir('laravel_app')) {
    $files = scandir('laravel_app');
    foreach ($files as $file) {
        if (is_dir('laravel_app/' . $file)) {
            echo "📁 [FOLDER] $file <br>";
        } else {
            echo "📄 [FILE] $file <br>";
        }
    }
} else {
    echo "Folder 'laravel_app' tidak ditemukan.";
}
?>
