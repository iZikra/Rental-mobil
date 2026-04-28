<?php
// Script untuk mengekstrak file zip di hosting
$files = ['root_files.zip'];

foreach ($files as $file) {
    if (file_exists($file)) {
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo('./');
            $zip->close();
            echo "✅ Berhasil mengekstrak: $file <br>";
        } else {
            echo "❌ Gagal membuka: $file <br>";
        }
    } else {
        echo "ℹ️ File tidak ditemukan: $file <br>";
    }
}
echo "<b>Proses Selesai!</b>";
?>
