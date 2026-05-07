<?php
$zip = new ZipArchive;
$res = $zip->open('update_infinity_free.zip');
if ($res === TRUE) {
  $zip->extractTo(__DIR__);
  $zip->close();
  echo 'Update berhasil diekstrak!';
} else {
  echo 'Gagal mengekstrak update.';
}
?>