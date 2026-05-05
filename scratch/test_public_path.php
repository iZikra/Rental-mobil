<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
echo "public_path: " . public_path() . "\n";
echo "base_path: " . base_path() . "\n";
