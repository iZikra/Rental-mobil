<?php
// Script Khusus Ekstrak Gambar
echo "<h3>🖼️ Memulai Proses Pemulihan Gambar...</h3>";

$zipFile = 'gambar_mobil.zip';
$targetDir = __DIR__ . '/public/storage/mobil_images';

// Buat folder jika belum ada
if (!file_exists(__DIR__ . '/public/storage')) {
    mkdir(__DIR__ . '/public/storage', 0755, true);
}
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (file_exists($zipFile)) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($targetDir);
        $zip->close();
        echo "✅ Berhasil mengekstrak puluhan gambar ke: <b>public/storage/mobil_images</b><br>";
    } else {
        echo "❌ Gagal membuka file zip gambar.<br>";
    }
} else {
    echo "⚠️ File <b>$zipFile</b> tidak ditemukan! Pastikan Anda sudah mengunggahnya.<br>";
}

echo "<hr>";
echo "<h3>✨ SELESAI! ✨</h3>";
echo "<p>Silakan buka website Anda di Mode Samaran dan tekan <b>Ctrl + F5</b>. Gambar pasti muncul!</p>";
