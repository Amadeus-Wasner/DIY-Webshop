<?php
session_start();
require '../config/db_connection.php'; // Datenbankverbindung
require_once '../vendor/phpmailer/autoload.php';
require_once '../models/User.php';
require_once '../extern/google_auth/PHPGangsta/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailOrUsername = $_POST['email_or_username'];
    $hashedPassword = $_POST['hashed_password'] ?? null;
    $totpCode = $_POST['totp_code'];

    if (!$hashedPassword) {
        header("Location: ../views/login_view.php?error=Fehler bei der Passwortübertragung.");
        exit();
    }

    // Benutzer aus der Datenbank laden
    $user = User::findByEmailOrUsername($link, $emailOrUsername);
    if ($user && password_verify($hashedPassword, $user['password'])) {
        $secret = $user['secret'];
        $checkResult = $ga->verifyCode($secret, $totpCode, 2); // 2*30 Sekunden Toleranz

        if ($checkResult) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Benutzerpunkte aktualisieren
            User::addPoints($link, $user['id'], 5);

            // Login-Protokoll
            User::logEvent($link, $user['id'], 'login', 'Login erfolgreich, 5 Punkte hinzugefügt.');

            // Weiterleitung
            header("Location: ../views/home.php");
            exit();
        } else {
            header("Location: ../views/login_view.php?error=Ungültiger TOTP-Code.");
            exit();
        }
    } else {
        header("Location: ../views/login_view.php?error=Ungültige Email, Benutzername oder Passwort.");
        exit();
    }
}
?>
