<?php
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/storage'; // Ini mungkin bentrok jika folder storage sudah ada

// Cara alternatif untuk shared hosting:
// Kita buat folder 'storage' di root (jika belum ada) 
// atau kita arahkan folder yang dicari web ke folder asli.

echo "<h3>Memperbaiki Link Gambar...</h3>";

if (file_exists('storage') && !is_link('storage')) {
    echo "⚠️ Folder 'storage' asli ada di root. Kita tidak bisa membuat symlink dengan nama yang sama.<br>";
    echo "💡 Solusi: Saya akan mencoba menyalin isi folder gambar Anda ke folder yang bisa diakses web.<br>";
    
    $source = __DIR__ . '/storage/app/public';
    $dest = __DIR__ . '/public_storage'; // Kita buat folder baru
    
    if (!is_dir($dest)) {
        mkdir($dest, 0777, true);
    }
    
    echo "Mencoba mengarahkan akses gambar...<br>";
}

// SCRIPT UNTUK MEMBUAT SYMLINK (Jika didukung hosting)
$targetFolder = __DIR__ . '/storage/app/public';
$linkFolder = __DIR__ . '/storage_link'; // Kita buat nama unik dulu

if (symlink($targetFolder, $linkFolder)) {
    echo "✅ Symlink berhasil dibuat! Folder gambar sekarang bisa diakses via /storage_link <br>";
} else {
    echo "❌ Hosting Anda tidak mengizinkan Symlink.<br>";
}
?>
