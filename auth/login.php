<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = "Bitte Benutzername und Passwort eingeben.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();


        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: ../dashboard.php");
            exit;
        } else {
            $errors[] = "Benutzername oder Passwort falsch.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>

        <?php if (isset($_GET['registered'])): ?>
            <div class="success-box">Registrierung erfolgreich! Bitte einloggen.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm" novalidate>
            <label for="username">Benutzername</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Passwort</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Einloggen</button>
        </form>

        <p>Noch kein Konto? <a href="register.php">Registrieren</a></p>
    </div>

    <script src="../assets/js/validation.js"></script>
</body>
</html>