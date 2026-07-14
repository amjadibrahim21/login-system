# Login System PHP

## Beschreibung
Dieses Projekt ist ein webbasiertes Login-System mit PHP und MySQL.
Benutzer können sich registrieren, anmelden und eigene Einträge 
verwalten (erstellen, bearbeiten, löschen).

## Technologien
- PHP 8.0
- MySQL
- HTML / CSS
- JavaScript

## Funktionen
- Benutzerregistrierung mit Validierung
- Login / Logout mit Session-Management
- Sicheres Passwort-Hashing mit bcrypt (password_hash)
- Datenbankanbindung über PDO
- CRUD-Operationen (Einträge erstellen, lesen, bearbeiten, löschen)
- Clientseitige Formularvalidierung mit JavaScript
- XSS-Schutz

## Installation
1. XAMPP herunterladen und installieren
2. Apache und MySQL in XAMPP starten
3. Projekt in den Ordner `/Applications/XAMPP/xamppfiles/htdocs/` kopieren
4. `config/db.php` aus `config/db.example.php` erstellen und anpassen
5. SQL-Datei `database/schema.sql` in phpMyAdmin importieren
6. Im Browser öffnen: `http://localhost/login-system/auth/register.php`

## Autor
Amjad Ibrahim

