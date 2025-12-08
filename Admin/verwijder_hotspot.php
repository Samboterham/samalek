<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'conn.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM ua_extraInformatie WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: informatie.php");
    exit;
}
?>
