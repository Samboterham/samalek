<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $hoofd_id = $_POST['hoofd_id'];
    $informatie = $_POST['informatie'];
    $extra_image = $_POST['extra_image'];
    $punt_positie_x = $_POST['punt_positie_x'];
    $punt_positie_y = $_POST['punt_positie_y'];

    $stmt = $pdo->prepare("INSERT INTO ua_extraInformatie (id_hoofdInfo, informatie, image, punt_positie_x, punt_positie_y)
                       VALUES (:hoofd_id, :informatie, :image, :x, :y)");

    $stmt->execute([
        ':hoofd_id' => $hoofd_id,
        ':informatie' => $informatie,
        ':image' => $extra_image,
        ':x' => $punt_positie_x,
        ':y' => $punt_positie_y
    ]);

        echo "Gegevens bijgewerkt!";
    header("Location: informatie.php");
}
?>