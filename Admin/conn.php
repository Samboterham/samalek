
<?php
$dsn = "mysql:host=localhost;dbname=ua_database;charset=utf8";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Query
$sql = "SELECT * FROM ua_informatie";
$stmt = $pdo->query($sql);  // PDO equivalent of mysqli->query()

$sql1 = "SELECT * FROM ua_extraInformatie";
$stmt_ext = $pdo->query($sql1);  // PDO equivalent of mysqli->query()


?>

