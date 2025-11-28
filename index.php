<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);


$sql = "SELECT * FROM ua_informatie";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<header>
    <div class="header-container">
        <div class="header-content">
            <img class="ua-logo" src="assets/images/ualogo.png">
            <div class="nav">
                <ul>

                </ul>
            </div>
        </div>
</header>

<body>
    <div class="grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            $image_id = $row['id'];
            $sql_1 = "SELECT * FROM ua_extrainformatie WHERE id_hoofdInfo = $image_id";
            $hotspots = $conn->query($sql_1);
            ?>

            <div class="card">
                <div class="card-inner">
                    <div class="card-front">
                        <!-- attach useful data-* attributes so JS can populate the global popup -->
                        <img
                          src="<?= htmlspecialchars($row['image']) ?>"
                          alt="<?= htmlspecialchars($row['id']) ?>"
                          data-id="<?= $row['id'] ?>"
                          data-catalogus="<?= htmlspecialchars($row['catalogusnummer'] ?? '') ?>"
                          data-beschrijving="<?= htmlspecialchars($row['beschrijving'] ?? '') ?>"
                        >

                        <?php while ($h = $hotspots->fetch_assoc()): ?>
                            <button class="hotspot"
                                style="left: <?= $h['punt_positie_x'] ?>%; top: <?= $h['punt_positie_y'] ?>%;"
                                data-text="<?= htmlspecialchars($h['informatie']) ?>"
                                data-text-ctl="<?= htmlspecialchars($h['catalogusnummer']) ?>"
                                data-img="<?= htmlspecialchars($h['image']) ?>">
                                <div class="plus"></div>
                            </button>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
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
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="" style="max-width:100%; height:auto; display:block; margin-bottom:12px;">
                    </div>
                    <?php if (!empty($row['catalogusnummer'])): ?>
                        <div style="margin-top:8px"><strong>Catalogusnummer:</strong> <?= htmlspecialchars($row['catalogusnummer']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($row['beschrijving'])): ?>
                        <div style="margin-top:8px"><strong>Beschrijving:</strong> <?= htmlspecialchars($row['beschrijving']) ?></div>
                    <?php else: ?>
                        <div style="margin-top:8px">No description available.</div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Hotspot Modal -->
    <div id="hotspotModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <p id="modalText"></p>
            <p id="modalTextCtl"></p>
            <img id="modalImage" style="display:none; max-width:100%; height:auto;">
        </div>
    </div>
</body>

</html>

<?php $conn->close(); ?>
