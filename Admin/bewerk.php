<?php
require 'conn.php';

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

<link rel="stylesheet" href="style.css">

<header>
    <a href="informatie.php" class="btn btn-bewerk">Terug</a>

    <div class="img-container">
        <img src="assets/images/ualogo.png" alt="UA Logo">
    </div>
</header>




<form method="POST" action="update.php" class="bewerk" id="updateForm">

    <div class="bewerk_container">
        <h1>Hoofd informatie</h1>

        <input type="hidden" name="id" value="<?= $Info['id'] ?>">

       
        <div class="form-group">
            <label>Catalogusnummer</label><br>
            <input type="text" class="form-control" 
                   name="catalogusnummer" 
                   value="<?= $Info['catalogusnummer'] ?>">
        </div><br>

      
        <div class="form-group">
            <label>Beschrijving</label><br>
            <textarea class="form-control" name="beschrijving"><?= $Info['beschrijving'] ?></textarea>
        </div><br>

   
        <div class="form-group">
            <label>Image</label><br>
            <input type="text" class="form-control" 
                   name="image" 
                   value="<?= $Info['image'] ?>">
        </div><br>

        <img class="img" src="<?= $Info['image'] ?>" alt="Main Image">
    </div>




    <div class="bewerk_container">
        <h1>Extra informatie (hotspots)</h1>

        <?php if (!empty($hotspots)): ?>

            <?php foreach ($hotspots as $extra): ?>
                <div class="hotspot-box">

                    <b>Hotspot ID: <?= $extra['id'] ?></b><br><br>

                    <div class="form-group">
                        <label>Informatie</label>
                        <textarea class="form-control"
                                  name="extra[<?= $extra['id'] ?>][informatie]"><?= $extra['informatie'] ?></textarea>
                    </div><br>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="text" class="form-control"
                               name="extra[<?= $extra['id'] ?>][image]"
                               value="<?= $extra['image'] ?>">
                    </div><br>

                    <div class="form-group">
                        <label>Positie X</label>
                        <input type="text" class="form-control"
                               name="extra[<?= $extra['id'] ?>][punt_positie_x]"
                               value="<?= $extra['punt_positie_x'] ?>">
                    </div><br>

                    <div class="form-group">
                        <label>Positie Y</label>
                        <input type="text" class="form-control"
                               name="extra[<?= $extra['id'] ?>][punt_positie_y]"
                               value="<?= $extra['punt_positie_y'] ?>">
                    </div><br>

                    <a href="verwijder_hotspot.php?id=<?= $extra['id'] ?>"
                       onclick="return confirm('Weet u zeker dat u dit item wilt verwijderen?')" 
                       class="ops-btn">
                        Verwijder
                    </a>

                    <hr class="hr">

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            Geen extra informatie<br>
        <?php endif; ?>

    </div>
</form>

<div class="bewerk_container">
    <button type="submit" form="updateForm" name="opslaan" class="ops-btn" style="width: 750px;">Opslaan</button>

    <form action="hotspot.php" method="GET" style="display: inline;">
        <input type="hidden" name="hoofd_id" value="<?= $Info['id'] ?>">
        <button type="submit" class="ops-btn" style="width: 750px; ">Hotspot toevoegen</button>
    </form>
</div>
