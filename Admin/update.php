<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'conn.php';

$uploadDir = 'assets/images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- MAIN INFO ---
    $id = $_POST['id'];
    $catalogusnummer = $_POST['catalogusnummer'];
    $beschrijving = $_POST['beschrijving'];

    // Handle main image upload
    $image = $_POST['current_image'];
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($_FILES['image_file']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image = $targetPath;
        }
    }

    // Update main information
    try {
        $stmt = $pdo->prepare("
            UPDATE ua_informatie
            SET catalogusnummer = :catalogusnummer,
                beschrijving = :beschrijving,
                image = :image
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':catalogusnummer' => $catalogusnummer,
            ':beschrijving' => $beschrijving,
            ':image' => $image
        ]);
    } catch (PDOException $e) {
        die("Error updating main info: " . $e->getMessage());
    }

    if (isset($_POST['extra']) && is_array($_POST['extra'])) {
        foreach ($_POST['extra'] as $hotspotId => $hotspotData) {
            $informatie = $hotspotData['informatie'] ?? '';
            $hotspotImage = $hotspotData['image'] ?? '';
            $puntX = $hotspotData['punt_positie_x'] ?? '';
            $puntY = $hotspotData['punt_positie_y'] ?? '';

            try {
                $stmtHotspot = $pdo->prepare("
                    UPDATE ua_extraInformatie
                    SET informatie = :informatie,
                        image = :image,
                        punt_positie_x = :x,
                        punt_positie_y = :y
                    WHERE id = :id
                ");

                $stmtHotspot->execute([
                    ':id' => $hotspotId,
                    ':informatie' => $informatie,
                    ':image' => $hotspotImage,
                    ':x' => $puntX,
                    ':y' => $puntY
                ]);
            } catch (PDOException $e) {
                die("Error updating hotspot: " . $e->getMessage());
            }
        }
    }


    header("Location: bewerk.php?id=" . $id);
    exit;
}
?>
