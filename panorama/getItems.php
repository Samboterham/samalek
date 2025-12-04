<?php
header("Content-Type: application/json");

$pdo = new PDO("mysql:host=localhost;dbname=ua_database;charset=utf8", "root", "");

$stmt = $pdo->query("SELECT id, catalogusnummer FROM ua_informatie");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
