<?php
// Fehleranzeige aktivieren (nur während der Entwicklung, später deaktivieren!)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung laden
require_once '../config/db_connection.php';
session_start();

// Funktion: Passwort-Validierung
function validatePassword($password) {
    return strlen($password) >= 9 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/\d/', $password);
}

// Fehler-Array initialisieren
$errors = [];

// Verarbeitung der Registrierung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // E-Mail validieren
    $password = $_POST['password'];

    // Eingaben prüfen
    if (!$email) {
        $errors[] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
    }
    if (!validatePassword($password)) {
        $errors[] = 'Das Passwort muss mindestens 9 Zeichen lang sein und Großbuchstaben, Kleinbuchstaben sowie eine Zahl enthalten.';
    }

    // Wenn keine Fehler vorliegen, Benutzer speichern
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();

            // Prüfen, ob die E-Mail bereits registriert ist
            $stmt = $pdo->prepare('SELECT * FROM Kunden WHERE Email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Diese E-Mail-Adresse ist bereits registriert.';
            } else {
                // Passwort hashen
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                // Benutzer speichern
                $stmt = $pdo->prepare('INSERT INTO Kunden (Email, Passwort) VALUES (?, ?)');
                $stmt->execute([$email, $passwordHash]);

                // Erfolgreich registriert, Weiterleitung
                $_SESSION['user_id'] = $pdo->lastInsertId();
                header('Location: ../views/login_view.php?success=1');
                exit;
            }
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
    <title>Registrierung</title>
</head>
<body>
    <h1>Registrieren</h1>

    <!-- Fehler anzeigen -->
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Link zurück zum Formular -->
    <a href="../views/register_view.php">Zurück</a>
</body>
</html>
