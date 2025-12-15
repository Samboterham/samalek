<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panorama</title>
    <link rel="icon" type="image/png" href="Assets/images/ua_favicon.png">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<?php
require 'conn.php';
include 'assets/includes/slider-header.php'
    ?>

<body>
    <div class="grid" id="panoramaFotos">
        <?php $page = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>

            <div id="card-<?= $page ?>"></div>
            <?php
            $image_id = $row['id'];
            $sql_1 = "SELECT * FROM ua_extrainformatie WHERE id_hoofdInfo = $image_id";
            $hotspots = $conn->query($sql_1);
            ?>

            <div class="card" id="card-<?= $page ?>">
                <div class="card-inner">
                    <div class="card-front">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['id']) ?>"
                            data-id="<?= $row['id'] ?>"
                            data-catalogus="<?= htmlspecialchars($row['catalogusnummer'] ?? '') ?>"
                            data-beschrijving="<?= htmlspecialchars($row['beschrijving'] ?? '') ?>">
                        <?php while ($h = $hotspots->fetch_assoc()): ?>
                            <button class="hotspot"
                                style="left: <?= $h['punt_positie_x'] ?>%; top: <?= $h['punt_positie_y'] ?>%;"
                                data-text="<?= htmlspecialchars($h['informatie']) ?>"
                                data-text-ctl="<?= htmlspecialchars($h['catalogusnummer']) ?>"
                                data-img="<?= htmlspecialchars($h['image']) ?>"
                                style="<?= ($row['id'] === '1') ? 'width:200px;' : '' ?>">
                                <div class="plus"></div>
                            </button>
                            <?php

                            ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php $page++; ?>
        <?php endwhile; ?>
    </div>
    <div class="panorama-minimap" id="panoramaMinimap">
        <div class="panorama-minimap-track" id="panoramaMinimapTrack">
            <div class="panorama-minimap-viewport" id="panoramaMinimapViewport"></div>
        </div>
    </div>

    <div class="overlay" id="globalOverlay" aria-hidden="true"></div>
    <div id="globalPopup" class="popup" role="dialog" aria-modal="true" aria-hidden="true">
        <img class="magnify">
        <button id="closePopup" class="close" aria-label="Close">&times;</button>
        <button id="toggleMagnify" class="toggle-magnify" aria-label="Toggle Magnifier">🔍</button>

        <div id="popupContent" class="popup-content">

            <?php mysqli_data_seek($result, 0); ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="popup-item" data-id="<?= $row['id'] ?>">
                    <div class="img-magnifier-container">
                        <img id="popup-img-<?= $row['id'] ?>" src="<?= htmlspecialchars($row['image']) ?>" alt=""
                            style="max-width:100%; height:auto; display:block; margin-bottom:12px;">
                    </div>
                    <?php if (!empty($row['catalogusnummer'])): ?>
                        <div style="margin-top:8px"><strong>Catalogusnummer:</strong>
                            <?= htmlspecialchars($row['catalogusnummer']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($row['beschrijving'])): ?>
                        <div style="margin-top:8px"><strong>Beschrijving:</strong>
                            <?= htmlspecialchars($row['beschrijving']) ?>
                        </div>
                    <?php else: ?>
                        <div style="margin-top:8px">No description available.</div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="hotspotModal" class="modal">
        <div class="modal-content">
            <div class="modal-text">
                <b><label>Informatie:</label></b>
                <p id="modalText"></p>
                <div id="modalCatalog" style="display:none;">
                    <b><label>Catalogusnummer:</label></b>
                    <p id="modalTextCtl"></p>
                </div>
            </div>
            <div class="modal-image">
                <img id="modalImage" style="display:none; max-width:100%; height:auto;">
            </div>
            <span id="closeModal" class="close">&times;</span>
        </div>
    </div>

    <div id="colofonModal" class="modal">
        <div class="modal-content">
            <div class="modal-text">
                <b><label>Colofon:</label></b>
                <p><strong>Vervaardiger:</strong> Bos, J., tekenaar/graficus</p>
                <p><strong>Datering:</strong> 1859</p>
                <p><strong>Materiaalsoort:</strong> Prent</p>
                <p><strong>Uitgever:</strong> Herfkens en Zoon, Wed., uitgever</p>
            </div>
            <span id="closeColofon" class="close">&times;</span>
        </div>
    </div>


</body>
<script>
    var items = <?php echo json_encode($rows); ?>;
    console.log(items);

</script>


</html>

<?php $conn->close(); ?>