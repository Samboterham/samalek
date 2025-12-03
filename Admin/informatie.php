<?php
require 'conn.php';

$id = $_POST['id'] ?? 0.00;
$image = $_POST['image'] ?? "";
$beschrijving = $_POST['beschrijving'] ?? "";
$catalogusummer = $_POST['catalogusummer'] ?? 0.00;


$stmt = $pdo->query("SELECT * FROM ua_informatie");



?>
<link rel="stylesheet" href="style.css">
<header>
    <a href="crud.php" class="btn btn-bewerk">Terug</a>
    <div class="img-container">
        <img src="assets/images/ualogo.png" alt="UA Logo">
    </div>
</header>
<div class="container">
    <h1>UA Informatie</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Catalogusnummer</th>
                <th>Beschrijving</th>
                <th>Image</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="line">

                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['catalogusnummer'] ?></td>
                    <td><?php echo $row['beschrijving'] ?></td>
                    <td><img src="<?= htmlspecialchars($row['image']) ?>" class="img" alt="UA Image"></td>
                    <td class="btn">
                        <a href="bewerk.php?id=<?php echo $row['id'] ?>" class="btn btn-bewerk">Bewerk</a>
                       
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>