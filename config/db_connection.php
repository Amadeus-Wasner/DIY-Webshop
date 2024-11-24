<?php
// Datenbank-Konfiguration
$host = 'localhost';
$db = 'db_webshop';
$user = 'root';
$password = '';
$port = 3306; // Standardport für MySQL

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

try {
    // Datenbankverbindung erstellen
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    if (defined('DEBUG') && DEBUG) {
        die('Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage());
    } else {
        die('Verbindung zur Datenbank fehlgeschlagen.');
    }
}

// Globale Zugriffsfunktion
function getDBConnection()
{
    global $pdo;
    return $pdo;
}
?>
