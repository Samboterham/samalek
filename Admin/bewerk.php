<?php
session_start();
require 'conn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo 'error';
    exit;
}

$id = $_GET['id'];


$stmtInfo = $pdo->prepare("SELECT * FROM ua_informatie WHERE id = :id");
$stmtInfo->execute([':id' => $id]);
$Info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

if (!$Info) {
    echo "Niks gevonden";
    exit;
}


$stmtExtra = $pdo->prepare("SELECT * FROM ua_extraInformatie WHERE id_hoofdInfo = :id");
$stmtExtra->execute([':id' => $id]);
$hotspots = $stmtExtra->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/ua_favicon.png">
    <link rel="stylesheet" href="style.css">
    <title>Bewerk item</title>
</head>
<body>
    <header class="admin-header">
        <div class="branding">
            <div class="img-container">
                <img src="assets/images/ualogo.png" alt="Utrecht's Archief">
            </div>
            <div class="branding-copy">
                <span>Samalek Admin</span>
                <strong>Item bewerken</strong>
            </div>
        </div>
        <a href="informatie.php" class="btn btn-bewerk">Terug naar overzicht</a>
    </header>
    <main class="page-shell">
        <section class="card page-hero">
            <span class="badge">Record <?= htmlspecialchars($Info['id']) ?></span>
            <h1><?= htmlspecialchars($Info['catalogusnummer'] ?? 'Onbekend item') ?></h1>
            <p>Werk de hoofdgegevens en gekoppelde hotspots bij en upload nieuwe afbeeldingen rechtstreeks vanaf je apparaat.</p>
        </section>
        <form method="POST" action="update.php" id="updateForm" class="card editor-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $Info['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($Info['image']) ?>">
            <section class="editor-layout">
                <article class="editor-panel">
                    <h2>Hoofd informatie</h2>
                    <div class="editor-fields">
                        <label>Catalogusnummer
                            <input type="text" class="form-control" name="catalogusnummer" value="<?= htmlspecialchars($Info['catalogusnummer']) ?>">
                        </label>
                        <label>Beschrijving
                            <textarea class="form-control" name="beschrijving" rows="4"><?= htmlspecialchars($Info['beschrijving']) ?></textarea>
                        </label>
                        <div class="file-field">
                            <label for="image_file">Hoofdafbeelding</label>
                            <div class="current-image">
                                <img class="img" src="<?= htmlspecialchars($Info['image']) ?>" alt="Huidige afbeelding">
                                <span><?= basename($Info['image']) ?></span>
                            </div>
                            <div id="main-image-preview" style="margin-bottom: 10px; display: none;">
                                <img id="main-preview-img" src="" alt="Nieuwe afbeelding preview" style="max-width: 300px; max-height: 300px; border: 1px solid #ccc; border-radius: 5px;">
                            </div>
                            <input type="file" id="image_file" name="image_file" accept="image/*">
                            <label for="image_file" class="upload-btn">Kies Nieuwe Afbeelding</label>
                        </div>
                    </div>
                </article>
                <article class="editor-panel">
                    <h2>Hotspots</h2>
                    <div class="hotspot-grid">
                        <?php if (!empty($hotspots)): ?>
                            <?php foreach ($hotspots as $extra): ?>
                                <div class="hotspot-card">
                                    <div class="hotspot-heading">
                                        <span>Hotspot <?= $extra['id'] ?></span>
                                        <a href="verwijder_hotspot.php?id=<?= $extra['id'] ?>" class="ops-btn" onclick="return confirm('Weet u zeker dat u dit item wilt verwijderen?')">Verwijder</a>
                                    </div>
                                    <input type="hidden" name="extra[<?= $extra['id'] ?>][current_image]" value="<?= htmlspecialchars($extra['image']) ?>">
                                    <label>Informatie
                                        <textarea class="form-control" name="extra[<?= $extra['id'] ?>][informatie]" rows="3"><?= htmlspecialchars($extra['informatie']) ?></textarea>
                                    </label>
                                    <div class="file-field">
                                        <label for="extra-image-<?= $extra['id'] ?>">Hotspot afbeelding</label>
                                        <?php if (!empty($extra['image'])): ?>
                                            <div class="current-image">
                                                <img class="img" src="<?= htmlspecialchars($extra['image']) ?>" alt="Hotspot afbeelding">
                                                <span><?= basename($extra['image']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div id="image-preview-<?= $extra['id'] ?>" style="margin-bottom: 10px; display: none;">
                                            <img id="preview-img-<?= $extra['id'] ?>" src="" alt="Image Preview" style="max-width: 300px; max-height: 300px; border: 1px solid #ccc; border-radius: 5px;">
                                        </div>
                                        <input type="file" id="extra-image-<?= $extra['id'] ?>" name="extra_image_<?= $extra['id'] ?>" accept="image/*" style="display: none;">
                                        <label for="extra-image-<?= $extra['id'] ?>" class="upload-btn">Kies Nieuwe Afbeelding</label>
                                    </div>
                                    <div class="hotspot-actions">
                                        <label>Positie X
                                            <input type="number" class="form-control" name="extra[<?= $extra['id'] ?>][punt_positie_x]" value="<?= htmlspecialchars($extra['punt_positie_x']) ?>" step="0.1" min="0" max="100">
                                        </label>
                                        <label>Positie Y
                                            <input type="number" class="form-control" name="extra[<?= $extra['id'] ?>][punt_positie_y]" value="<?= htmlspecialchars($extra['punt_positie_y']) ?>" step="0.1" min="0" max="100">
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Geen hotspots gekoppeld aan dit item.</p>
                        <?php endif; ?>
                    </div>
                </article>
            </section>
            <div class="editor-actions">
                <button type="submit" name="opslaan" class="ops-btn">Wijzigingen Opslaan</button>
                <a href="hotspot.php?hoofd_id=<?= $Info['id'] ?>" class="ops-btn" style="text-align:center;">Hotspot Toevoegen</a>
            </div>
        </form>
    </main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle preview for main image input
    const mainImageInput = document.getElementById('image_file');
    const mainPreviewDiv = document.getElementById('main-image-preview');
    const mainPreviewImg = document.getElementById('main-preview-img');

    mainImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainPreviewImg.src = e.target.result;
                mainPreviewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            mainPreviewDiv.style.display = 'none';
        }
    });

    // Handle preview for each hotspot image input
    const fileInputs = document.querySelectorAll('input[type="file"][id^="extra-image-"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const hotspotId = event.target.id.split('-')[2]; // Extract ID from id="extra-image-{id}"
            const previewDiv = document.getElementById('image-preview-' + hotspotId);
            const previewImg = document.getElementById('preview-img-' + hotspotId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewDiv.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
