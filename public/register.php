<?php
require_once '../config/database.php';
session_start();

$errors = [];

// Überprüfen, ob das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Validierung
    if (!$email) {
        $errors[] = 'Bitte eine gültige E-Mail-Adresse eingeben.';
    }
    if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = 'Das Passwort muss mindestens 9 Zeichen lang sein und Großbuchstaben, Kleinbuchstaben und Zahlen enthalten.';
    }

    if (empty($errors)) {
        // Prüfen, ob die E-Mail bereits existiert
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Diese E-Mail-Adresse ist bereits registriert.';
        } else {
            // Passwort hashen und Benutzer speichern
            $passwordHash = hash('sha512', $password);
            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
            $stmt->execute([$email, $passwordHash]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
</head>
<body>
    <h1>Registrieren</h1>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="register.php">
        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Passwort:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Registrieren</button>
    </form>
</body>
</html>
