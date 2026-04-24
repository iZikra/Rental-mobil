<?php
echo "<h3>Daftar Folder di Hosting</h3>";
$dir = "./";
$files = scandir($dir);

foreach ($files as $file) {
    if (is_dir($file)) {
        echo "📁 [FOLDER] $file <br>";
    } else {
        echo "📄 [FILE] $file <br>";
    }
}

echo "<h3>Cek isi folder 'rental-mobil' jika ada</h3>";
if (is_dir('rental-mobil')) {
    $subfiles = scandir('rental-mobil');
    foreach ($subfiles as $sf) {
        echo "--- $sf <br>";
    }
} else {
    echo "Folder 'rental-mobil' tidak ditemukan.";
}
?>
