<?php
require_once '../config/database.php';
session_start();

$errors = [];

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        // Benutzer in der Datenbank finden
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && hash('sha512', $password) === $user['password_hash']) {
            // Erfolgreiche Anmeldung
            $_SESSION['user_id'] = $user['id'];
            
            // Optional: Letzten Login und weitere Infos speichern
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'E-Mail oder Passwort ist falsch.';
        }
    } else {
        $errors[] = 'Bitte alle Felder ausfüllen.';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Anmelden</h1>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="login.php">
        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Passwort:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Anmelden</button>
    </form>
</body>
</html>
