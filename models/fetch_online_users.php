<?php
// Simulierte Anzahl der Online-Benutzer
header('Content-Type: application/json');
echo json_encode(['online_users' => rand(5, 50)]);
?>

<?php
session_start(); // Session starten, falls noch nicht aktiv

// Überprüfen, ob der Nutzer als einzigartig gezählt wurde
if (!isset($_SESSION['unique_user'])) {
    $_SESSION['unique_user'] = true; // Markiere den Nutzer als einzigartig

    // Initialisiere oder erhöhe die Anzahl der Online-Nutzer
    if (!isset($_SESSION['online_users'])) {
        $_SESSION['online_users'] = 1;
    } else {
        $_SESSION['online_users']++;
    }
}

// Anzahl der Online-Nutzer aus der Session abrufen
$onlineUsers = $_SESSION['online_users'];

// JSON-Ausgabe für AJAX oder andere Frontend-Abfragen
header("Content-Type: application/json");
echo json_encode(["online_users" => $onlineUsers]);
?>
