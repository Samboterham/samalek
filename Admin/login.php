<?php
session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username)) {
        $errors[] = 'Gebruikersnaam is verplicht.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Gebruikersnaam moet minimaal 3 karakters lang zijn.';
    } elseif (strlen($username) > 50) {
        $errors[] = 'Gebruikersnaam mag maximaal 50 karakters lang zijn.';
    }

    if (empty($password)) {
        $errors[] = 'Wachtwoord is verplicht.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Wachtwoord moet minimaal 6 karakters lang zijn.';
    } elseif (strlen($password) > 255) {
        $errors[] = 'Wachtwoord mag maximaal 255 karakters lang zijn.';
    }

    if (empty($errors)) {
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "ua_database";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        if ($conn->connect_error) {
            $errors[] = "Databaseverbinding mislukt: " . $conn->connect_error;
        } else {
            $stmt = $conn->prepare("SELECT id, wachtwoord FROM users WHERE gebruikersnaam = ?");
            if ($stmt === false) {
                $errors[] = "Databasefout: " . $conn->error;
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['wachtwoord'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $username;
                        $success = true;
                        header("Refresh: 2; url=slider.php");
                    } else {
                        $errors[] = 'Ongeldige gebruikersnaam of wachtwoord.';
                    }
                } else {
                    $errors[] = 'Ongeldige gebruikersnaam of wachtwoord.';
                }
                $stmt->close();
            }
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Foto Slider</title>
    <link rel="stylesheet" href="login.css">
    <script src="login.js" defer></script>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>Inloggen</h1>
                <p>Welkom terug</p>
            </div>

            <?php if ($success): ?>
                <div class="message success-message">
                    <strong>Succes!</strong> U bent ingelogd. U wordt omgeleid naar de slider...
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="message error-message">
                    <strong>Fout!</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="login.php" novalidate>
                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" placeholder="Voer uw gebruikersnaam in" value="<?= htmlspecialchars($username ?? '') ?>" required>
                    <span class="error-text" id="username-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" placeholder="Voer uw wachtwoord in" required>
                    <span class="error-text" id="password-error"></span>
                </div>

                <button type="submit" class="login-button">Enter</button>
            </form>
        </div>
    </div>
</body>

</html>
