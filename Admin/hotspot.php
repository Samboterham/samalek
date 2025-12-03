<?php
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
<link rel="stylesheet" href="style.css">
<header>
    <a href="bewerk.php?id=<?php echo $id; ?>" class="btn btn-bewerk">Terug</a>
    <div class="img-container">
        <img src="assets/images/ualogo.png" alt="UA Logo">
    </div>
</header>

<form method="POST" action="add_hotspot.php" class="bewerk">
    <div class="bewerk_container">
        <input type="hidden" name="hoofd_id" value="<?php echo $id; ?>">

        <h1>Hotspot toevoegen</h1>

        <div class="form-group">
            <label for="informatie">Informatie</label>
            <textarea class="form-control" id="informatie" name="informatie"></textarea>
        </div><br>

        <div class="form-group">
            <label>Image (click to set X/Y)</label><br>
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
            <label for="extra_image">Extra Image URL</label>
            <input type="text" class="form-control" id="extra_image" name="extra_image">
        </div><br>

        <button type="submit" class="ops-btn" name="opslaan">Opslaan</button>
    </div>
</form>

<script src="script.js"></script>
