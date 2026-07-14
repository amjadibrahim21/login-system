<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';


    if (strlen($username) < 3) {
        $errors[] = "Benutzername muss mindestens 3 Zeichen lang sein.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Ungültige E-Mail-Adresse.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Passwort muss mindestens 8 Zeichen lang sein.";
    }
    if ($password !== $passwordConfirm) {
        $errors[] = "Passwörter stimmen nicht überein.";
    }

    // Prüfen, ob Benutzername/E-Mail schon existiert
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Benutzername oder E-Mail bereits vergeben.";
        }
    }

    // Speichern
    if (empty($errors)) {
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        header("Location: login.php?registered=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrieren</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Registrieren</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" id="registerForm" novalidate>
            <label for="username">Benutzername</label>
            <input type="text" id="username" name="username" required minlength="3">
            <span class="js-error" id="usernameError"></span>

            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" required>
            <span class="js-error" id="emailError"></span>

            <label for="password">Passwort</label>
            <input type="password" id="password" name="password" required minlength="8">
            <span class="js-error" id="passwordError"></span>

            <label for="password_confirm">Passwort bestätigen</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
            <span class="js-error" id="passwordConfirmError"></span>

            <button type="submit">Registrieren</button>
        </form>

        <p>Schon ein Konto? <a href="login.php">Zum Login</a></p>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>
</html>