<?php
session_start();

// Eingaben bereinigen (XSS-Schutz)
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Einlogin Prüfen
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// falls nicht eingeloggt
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /login-system/auth/login.php");
        exit;
    }
}

// E-Mail validieren
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}