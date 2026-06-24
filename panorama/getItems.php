<?php

header("Content-Type: application/json");

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || preg_match('/^192\.168\.2/', $_SERVER['SERVER_ADDR'])) {
    define("DB_NAME", "databasenaam-offline");
    define("DB_USER", "offlineuser");
    define("DB_PASSWORD", "offlinepassword");
    define("DB_HOST", "offline host");
    $dsn = "mysql:host=localhost;dbname=ua_database;charset=utf8";
    $user = "root";
    $pass = "";
} else {
    $dsn = "mysql:host=localhost;dbname=u240457_ua_database;charset=utf8";
    $user = "u240457_ua_database";
    $pass = "LtUWcXPwKb3YZFCjBPjB";
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$stmt = $pdo->query("SELECT id, catalogusnummer FROM ua_informatie");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
