<?php
try {
    $pdo = new PDO('pgsql:host=aws-0-ap-southeast-1.pooler.supabase.com;port=5432;dbname=postgres', 'postgres', 'rental-mobil12', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected successfully to Supabase DB.\n";
    $stmt = $pdo->query("SELECT version()");
    echo $stmt->fetchColumn() . "\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
