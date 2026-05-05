<?php
$lines = file('scratch/generate_deploy.php');
$out='';
foreach($lines as $line) {
    if (strpos($line, "file_put_contents(\$webPhpPath") !== false) {
        $out .= $line . "
    // 3. Patch bootstrap/app.php for CSRF bypass
    \$appPhpPath = __DIR__ . '/bootstrap/app.php';
    if (file_exists(\$appPhpPath)) {
        \$appContent = file_get_contents(\$appPhpPath);
        if (strpos(\$appContent, 'bot/*') === false) {
            \$appContent = str_replace(\"'midtrans/webhook',\", \"'midtrans/webhook',
            'bot/*',
            'chatbot/*',\", \$appContent);
            file_put_contents(\$appPhpPath, \$appContent);
            echo \"✅ Berhasil membypass CSRF Chatbot di bootstrap/app.php<br>\";
        }
    }
";
        continue;
    }
    $out .= $line;
}
file_put_contents('scratch/generate_deploy.php', $out);
echo "generate_deploy.php updated.";
