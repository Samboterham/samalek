<?php
require 'conn.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
      $stmt = $pdo->prepare("DELETE FROM ua_informatie WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: informatie.php");
    }
}

?>