<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin(); // Nur für eingeloggte Benutzer zugänglich

$userId = $_SESSION['user_id'];
$errors = [];
$editEntry = null;

// --- CREATE: neuen Eintrag speichern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = sanitize($_POST['title'] ?? '');
    $content = sanitize($_POST['content'] ?? '');

    if (strlen($title) < 2) {
        $errors[] = "Titel muss mindestens 2 Zeichen lang sein.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO entries (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $title, $content]);
        header("Location: dashboard.php");
        exit;
    }
}

// --- UPDATE: bestehenden Eintrag ändern ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $entryId = (int) $_POST['entry_id'];
    $title = sanitize($_POST['title'] ?? '');
    $content = sanitize($_POST['content'] ?? '');

    if (strlen($title) < 2) {
        $errors[] = "Titel muss mindestens 2 Zeichen lang sein.";
    } else {
        // WICHTIG: user_id in der WHERE-Klausel, damit niemand fremde Einträge ändern kann
        $stmt = $pdo->prepare("UPDATE entries SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $content, $entryId, $userId]);
        header("Location: dashboard.php");
        exit;
    }
}

// --- DELETE: Eintrag löschen ---
if (isset($_GET['delete'])) {
    $entryId = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM entries WHERE id = ? AND user_id = ?");
    $stmt->execute([$entryId, $userId]);
    header("Location: dashboard.php");
    exit;
}

// --- Für Bearbeiten: Eintrag laden, der editiert werden soll ---
if (isset($_GET['edit'])) {
    $entryId = (int) $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM entries WHERE id = ? AND user_id = ?");
    $stmt->execute([$entryId, $userId]);
    $editEntry = $stmt->fetch();
}

// --- READ: alle Einträge des Benutzers laden ---
$stmt = $pdo->prepare("SELECT * FROM entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$entries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h2>Willkommen, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
            <a href="auth/logout.php" class="logout-btn">Logout</a>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formular: Erstellen oder Bearbeiten -->
        <div class="form-container">
            <h3><?= $editEntry ? 'Eintrag bearbeiten' : 'Neuer Eintrag' ?></h3>
            <form method="POST" id="entryForm" novalidate>
                <input type="hidden" name="action" value="<?= $editEntry ? 'update' : 'create' ?>">
                <?php if ($editEntry): ?>
                    <input type="hidden" name="entry_id" value="<?= $editEntry['id'] ?>">
                <?php endif; ?>

                <label for="title">Titel</label>
                <input type="text" id="title" name="title" required minlength="2"
                       value="<?= $editEntry ? htmlspecialchars($editEntry['title']) : '' ?>">
                <span class="js-error" id="titleError"></span>

                <label for="content">Inhalt</label>
                <textarea id="content" name="content" rows="4"><?= $editEntry ? htmlspecialchars($editEntry['content']) : '' ?></textarea>

                <button type="submit"><?= $editEntry ? 'Speichern' : 'Hinzufügen' ?></button>
                <?php if ($editEntry): ?>
                    <a href="dashboard.php" class="cancel-btn">Abbrechen</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Liste aller Einträge -->
        <div class="entries-list">
            <h3>Deine Einträge</h3>
            <?php if (empty($entries)): ?>
                <p>Noch keine Einträge vorhanden.</p>
            <?php else: ?>
                <?php foreach ($entries as $entry): ?>
                    <div class="entry-card">
                        <h4><?= htmlspecialchars($entry['title']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($entry['content'])) ?></p>
                        <small>Erstellt: <?= htmlspecialchars($entry['created_at']) ?></small>
                        <div class="entry-actions">
                            <a href="dashboard.php?edit=<?= $entry['id'] ?>" class="edit-btn">Bearbeiten</a>
                            <a href="dashboard.php?delete=<?= $entry['id'] ?>" class="delete-btn"
                               onclick="return confirm('Wirklich löschen?')">Löschen</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/validation.js"></script>
</body>
</html>