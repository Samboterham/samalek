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
    <link rel="stylesheet" href="style.css" class="stylesheet">
    <script src="script.js" defer></script>
</head>

<body>
    <div class="overlay"></div>
    <div class="grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-inner">
                    <div class="card-front">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['id']) ?>">
                    </div>
                    <div class="card-back">
                        <p><?= htmlspecialchars($row['catalogusnummer']) ?></p>
                        <p><?= htmlspecialchars($row['beschrijving']) ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>

</html>
<?php $conn->close(); ?>