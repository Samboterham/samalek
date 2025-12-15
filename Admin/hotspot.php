<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'conn.php';

if (!isset($_GET['hoofd_id'])) {
    die("Hoofd ID ontbreekt!");
}

$id = intval($_GET['hoofd_id']);

// Fetch main info to get the image
$stmt = $pdo->prepare("SELECT * FROM ua_informatie WHERE id = :id");
$stmt->execute([':id' => $id]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
$image = $info['image']; // image URL

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotspot Toevoegen</title>
    <link rel="icon" type="image/png" href="assets/images/ua_favicon.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="admin-header">
        <div class="branding">
            <div class="img-container">
                <img src="assets/images/ualogo.png" alt="Utrecht's Archief">
            </div>
            <div class="branding-copy">
                <span>Samalek Admin</span>
                <strong>Hotspot Toevoegen</strong>
            </div>
        </div>
        <a href="informatie.php" class="btn btn-bewerk">Terug naar overzicht</a>
    </header>

<form method="POST" action="add_hotspot.php" class="bewerk" enctype="multipart/form-data">
    <div class="bewerk_container">
        <input type="hidden" name="hoofd_id" value="<?php echo $id; ?>">

        <div class="form-group">
            <label for="informatie">Informatie</label>
            <textarea class="form-control" id="informatie" name="informatie"></textarea>
        </div><br>

        <div class="form-group">
            <label>Afbeelding (klik om X/Y te zetten)</label><br>
            <img id="hotspot-image" src="<?php echo $image; ?>"
                style="max-width:600px; border:1px solid #ccc; cursor:crosshair;">
            <div id="coords" style="margin-top:5px; font-family:monospace;"></div>
        </div><br>

        <div class="form-group">
            <label for="punt_positie_x">Positie X (%)</label>
            <input type="text" class="form-control" id="punt_positie_x" name="punt_positie_x">
        </div><br>

        <div class="form-group">
            <label for="punt_positie_y">Positie Y (%)</label>
            <input type="text" class="form-control" id="punt_positie_y" name="punt_positie_y">
        </div><br>

        <div class="form-group">
            <label for="extra_image">Extra Afbeelding</label>
            <div id="image-preview" style="margin-bottom: 10px; display: none;">
                <img id="preview-img" src="" alt="Image Preview" style="max-width: 300px; max-height: 300px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <input type="file" id="extra_image" name="extra_image" accept="image/*" style="display: none;">
            <button type="button" class="btn btn-bewerk" onclick="document.getElementById('extra_image').click();">Kies Afbeelding</button>
        </div><br>

        <button type="submit" class="ops-btn" name="opslaan">Opslaan</button>
    </div>
</form>

<script src="script.js"></script>
<script>
document.getElementById('extra_image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('preview-img');
            previewImg.src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
});
</script>
