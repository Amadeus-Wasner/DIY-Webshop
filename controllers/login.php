<?php
// Fehleranzeige aktivieren (nur für Entwicklung)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connection.php'; // Datenbankverbindung laden
session_start();
// Session-Daten überprüfen
error_log('Session User ID: ' . $_SESSION['user_id']);
error_log('Session User Email: ' . $_SESSION['user_email']);

$error = ''; // Fehlernachricht initialisieren

function aktualisierePunkte($pdo, $kundenID) {
    try {
        // Punkte hinzufügen oder erstellen
        $stmt = $pdo->prepare('
            INSERT INTO Punkte (KundenID, Punkte, LetzteAktualisierung)
            VALUES (?, 2, NOW())
            ON DUPLICATE KEY UPDATE Punkte = Punkte + 2, LetzteAktualisierung = NOW()
        ');
        $stmt->execute([$kundenID]);

        error_log("Punkte erfolgreich aktualisiert für KundenID: $kundenID");
    } catch (PDOException $e) {
        error_log('Fehler beim Punkte-Update: ' . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Benutzer-E-Mail
    $password = $_POST['password']; // Benutzer-Passwort
    $resolution = $_POST['resolution'] ?? 'Unbekannt'; // Bildschirmauflösung
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? ''; // User-Agent für Betriebssystem

    // Betriebssystem erkennen
    $os = 'Unbekannt';
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
                $updateStmt = $pdo->prepare('
                    UPDATE Kunden 
                    SET Bildschirmauflösung = ?, Betriebssystem = ? 
                    WHERE KundenID = ?
                ');
                $updateStmt->execute([$resolution, $os, $user['KundenID']]);
                error_log('Bildschirmauflösung und Betriebssystem aktualisiert.');

                // Punkte-Logik ausführen
                aktualisierePunkte($pdo, $user['KundenID']);

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
        error_log('Datenbankfehler: ' . $e->getMessage());
        $error = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
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
