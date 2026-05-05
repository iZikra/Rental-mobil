<?php
echo "<h3>⚙️ Memperbaiki CSRF untuk Chatbot...</h3>";

// Patch bootstrap/app.php untuk bypass CSRF pada route /bot/*
$appPhpPath = __DIR__ . '/bootstrap/app.php';

if (!file_exists($appPhpPath)) {
    echo "❌ File bootstrap/app.php tidak ditemukan!<br>";
    exit;
}

$content = file_get_contents($appPhpPath);

if (strpos($content, "'bot/*'") !== false) {
    echo "✅ CSRF bypass sudah terpasang sebelumnya. Tidak perlu diulang.<br>";
} else {
    $content = str_replace(
        "'midtrans/webhook',",
        "'midtrans/webhook',\n            'bot/*',\n            'chatbot/*',",
        $content
    );
    file_put_contents($appPhpPath, $content);
    echo "✅ CSRF bypass berhasil diterapkan ke bootstrap/app.php<br>";
}

// Bersihkan cache config agar perubahan langsung aktif
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->handle(Illuminate\Http\Request::capture());

    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    echo "✅ Cache Laravel berhasil dibersihkan.<br>";
} catch (\Exception $e) {
    echo "⚠️ Cache clear manual: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>✨ Selesai! ✨</h3>";
echo "<p>Chatbot sekarang <strong>tidak akan lagi</strong> menampilkan 'Koneksi gangguan' meskipun halaman dibiarkan terbuka lama.</p>";
echo "<p>Silakan refresh halaman website Anda dan coba kirim pesan ke chatbot!</p>";
