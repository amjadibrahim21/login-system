<?php
// Datenbank-Zugangsdaten
$host = 'localhost';
$dbname = 'login_system';
$dbuser = 'root';      // Standard bei XAMPP
$dbpass = '';          // Standard bei XAMPP: LEER (kein Passwort)

try {
    // PDO-Verbindung (sicherer als mysqli, unterstützt Prepared Statements einfach)
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}