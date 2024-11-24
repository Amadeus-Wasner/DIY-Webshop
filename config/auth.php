<?php
require_once 'db_connection.php';

// Beispiel für eine Funktion zur Benutzeranmeldung
function login($email, $password)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT * FROM Kunden WHERE Email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Passwort'])) {
        // Benutzer authentifiziert
        session_start();
        $_SESSION['user_id'] = $user['KundenID'];
        return true;
    }
    return false;
}

// Beispiel für Abmeldung
function logout()
{
    session_start();
    session_destroy();
}
?>
