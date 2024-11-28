<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_view.php');
    exit;
}

require_once '../config/db_connection.php';
$errors = [];

// Verarbeitung der Passwortänderung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];

    if (empty($password) || empty($passwordConfirm)) {
        $errors[] = 'Bitte füllen Sie beide Passwortfelder aus.';
    } elseif ($password !== $passwordConfirm) {
        $errors[] = 'Die Passwörter stimmen nicht überein.';
    } elseif (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = 'Das Passwort muss mindestens 9 Zeichen lang sein und Großbuchstaben, Kleinbuchstaben sowie eine Zahl enthalten.';
    } else {
        try {
            $pdo = getDBConnection();
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Passwort aktualisieren und FirstLogin-Flag zurücksetzen
            $stmt = $pdo->prepare('UPDATE Kunden SET Passwort = ?, FirstLogin = 0 WHERE KundenID = ?');
            $stmt->execute([$passwordHash, $_SESSION['user_id']]);

            // Weiterleitung zum Dashboard
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Datenbankfehler: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort ändern</title>
</head>
<body>
    <h1>Passwort ändern</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="first_login.php" method="post">
        <div>
            <label for="password">Neues Passwort:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="password_confirm">Passwort bestätigen:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit">Passwort ändern</button>
    </form>
</body>
</html>
