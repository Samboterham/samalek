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
    $punt_positie_x = $_POST['punt_positie_x'];
    $punt_positie_y = $_POST['punt_positie_y'];

    // Handle file upload
    $extra_image = '';
    if (isset($_FILES['extra_image']) && $_FILES['extra_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../panorama/Assets/images/'; // Adjust path as needed
        $file_name = basename($_FILES['extra_image']['name']);
        $target_file = $upload_dir . $file_name;

        // Check if file is an image
        $check = getimagesize($_FILES['extra_image']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['extra_image']['tmp_name'], $target_file)) {
                $extra_image = '/samalek/panorama/Assets/images/' . $file_name; // Full path for database
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

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
