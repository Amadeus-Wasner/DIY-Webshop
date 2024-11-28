<?php
// Fehleranzeige aktivieren (nur während der Entwicklung, später deaktivieren!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung laden
require_once '../config/db_connection.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Funktion: Passwort-Validierung
function validatePassword($password) {
    return strlen($password) >= 9 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/\d/', $password);
}


function generateStandardPassword($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

function sendRegistrationEmail($to, $password) {
    require '../vendor/autoload.php'; // Stelle sicher, dass der Pfad korrekt ist

    $mail = new PHPMailer(true);

    try {
        // Servereinstellungen
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sfserfedsfgsdfd@gmail.com';   // Gmail-Adresse
        $mail->Password   = 'afvf zled mwrp kqwn';         // App-Passwort
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Absender und Empfänger
        $mail->setFrom('sfserfedsfgsdfd@gmail.com', 'Webshop');
        $mail->addAddress($to);  // Empfängeradresse

        // E-Mail-Inhalt
        $mail->isHTML(true);
        $mail->Subject = 'Ihre Registrierung bei Webshop';
        $mail->Body    = "Vielen Dank für Ihre Registrierung! Ihr Standardpasswort lautet: <b>$password</b>";
        $mail->AltBody = "Vielen Dank für Ihre Registrierung! Ihr Standardpasswort lautet: $password";

        // E-Mail senden
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("E-Mail konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}");
        return false;
    }
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

    // Eingaben prüfen
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
    
            // Prüfen, ob die E-Mail bereits registriert ist
            $stmt = $pdo->prepare('SELECT * FROM Kunden WHERE Email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Diese E-Mail-Adresse ist bereits registriert.';
            } else {
                // Neuen Benutzer mit Standardpasswort registrieren
                $standardPassword = generateStandardPassword(10); // Standardpasswort generieren
                $passwordHash = password_hash($standardPassword, PASSWORD_BCRYPT); // Passwort verschlüsseln
    
                // Benutzer speichern
                $stmt = $pdo->prepare('INSERT INTO Kunden (Email, Passwort) VALUES (?, ?)');
                $stmt->execute([$email, $passwordHash]);
    
                // Bestätigungsmail senden
                if (sendRegistrationEmail($email, $standardPassword)) {
                    echo 'Registrierung erfolgreich. Eine Bestätigungsmail mit dem Standardpasswort wurde versendet.';
                } else {
                    echo 'Registrierung erfolgreich, aber die Bestätigungsmail konnte nicht versendet werden.';
                }
    
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