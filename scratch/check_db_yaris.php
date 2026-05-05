<?php
$p=new PDO('mysql:host=127.0.0.1;dbname=rental_mobil','root','');
$stmt=$p->query("SELECT merk, model, gambar FROM mobils WHERE model LIKE '%Yaris%' LIMIT 1");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
