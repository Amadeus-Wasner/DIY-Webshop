<?php
// Fehleranzeige aktivieren (nur für Entwicklung)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connection.php'; // Datenbankverbindung laden
session_start();

$error = ''; // Fehlernachricht initialisieren

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Benutzer-E-Mail
    $password = $_POST['password']; // Benutzer-Passwort
    $resolution = $_POST['resolution'] ?? 'Unbekannt'; // Bildschirmauflösung
    $userAgent = $_SERVER['HTTP_USER_AGENT']; // Browser-User-Agent

    // Bildschirmauflösung
    $resolution = $_POST['resolution'] ?? 'Unbekannt';
    error_log("Erfasste Bildschirmauflösung: " . $resolution);

    $os = 'Unbekannt';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (preg_match('/Windows/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
        $os = 'macOS';
    } elseif (preg_match('/Linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/Android/i', $userAgent)) {
        $os = 'Android';
    } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
        $os = 'iOS';
    }

    try {
        $pdo = getDBConnection();

        // Benutzer anhand der E-Mail-Adresse suchen
        $stmt = $pdo->prepare('SELECT * FROM Kunden WHERE Email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Passwort prüfen
            if (password_verify($password, $user['Passwort'])) {
                // Login erfolgreich: Session starten
                $_SESSION['user_id'] = $user['KundenID'];
                $_SESSION['user_email'] = $user['Email'];

                // Bildschirmauflösung und Betriebssystem speichern
                $updateStmt = $pdo->prepare('UPDATE Kunden SET Bildschirmauflösung = ?, Betriebssystem = ? WHERE KundenID = ?');
                $updateStmt->execute([$resolution, $os, $user['KundenID']]);

                // Weiterleitung zum Dashboard
                header('Location: ../views/dashboard.php');
                exit;
            } else {
                $error = 'Falsches Passwort.';
            }
        } else {
            $error = 'E-Mail-Adresse nicht gefunden.';
        }
    } catch (PDOException $e) {
        $error = 'Datenbankfehler: ' . $e->getMessage();
    }

    // Validierungsregeln für Benutzername und Passwort
    $emailValid = strlen($email) >= 5 && strpos($email, '@') !== false; // E-Mail prüfen
    $passwordValid = preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{9,}$/', $password); // Passwort prüfen

    // Eingabe validieren
    if (!$emailValid) {
        $error = 'Der Benutzername muss mindestens 5 Zeichen lang sein und ein "@" enthalten.';
    } elseif (!$passwordValid) {
        $error = 'Das Passwort muss mindestens 9 Zeichen lang sein und Großbuchstaben, Kleinbuchstaben und eine Zahl enthalten.';
    } else {
        try {
            $pdo = getDBConnection();

            // Benutzer anhand der E-Mail-Adresse suchen
            $stmt = $pdo->prepare('SELECT * FROM Kunden WHERE Email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Passwort prüfen
                if (password_verify($password, $user['Passwort'])) {
                    // Login erfolgreich: Session starten
                    $_SESSION['user_id'] = $user['KundenID'];
                    $_SESSION['user_email'] = $user['Email'];

                    // Weiterleitung zum Dashboard
                    header('Location: ../views/dashboard.php');
                    exit;
                } else {
                    $error = 'Falsches Passwort.';
                }
            } else {
                $error = 'E-Mail-Adresse nicht gefunden.';
            }
        } catch (PDOException $e) {
            $error = 'Datenbankfehler: ' . $e->getMessage();
        }
    }
} else {
    $error = 'Ungültige Anfrage.';
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <!-- Fehler anzeigen -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Zurück zum Login -->
    <a href="../views/login_view.php">Zurück</a>
</body>
</html>
