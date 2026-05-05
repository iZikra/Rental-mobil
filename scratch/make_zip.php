<?php
$zipFile = 'update_frontend_final.zip';
if (file_exists($zipFile)) {
    unlink($zipFile);
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Failed to create zip file");
}

function addFolderToZip($folder, &$zip) {
    if (!is_dir($folder)) return;
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(__DIR__) + 1);
            // Replace backslashes with forward slashes for zip internal paths
            $relativePath = str_replace('\\', '/', $relativePath);
            $zip->addFile($filePath, $relativePath);
        }
    }
}

addFolderToZip(__DIR__ . '/app', $zip);
addFolderToZip(__DIR__ . '/resources/views', $zip);
addFolderToZip(__DIR__ . '/public/build', $zip);

$zip->addFile(__DIR__ . '/routes/web.php', 'routes/web.php');
$zip->addFile(__DIR__ . '/clear_cache.php', 'clear_cache.php');
$zip->addFile(__DIR__ . '/deploy_frontend.php', 'deploy_frontend.php');

$zip->close();
echo "Zip created successfully. Structure preserved.\n";
