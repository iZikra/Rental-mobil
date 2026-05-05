<?php
$p=new PDO('mysql:host=127.0.0.1;dbname=rental_mobil','root','');
$stmt=$p->query('SELECT gambar FROM mobils LIMIT 5');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
