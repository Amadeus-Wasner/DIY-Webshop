<?php
$host = 'localhost';
$db = 'webshop';
$user = 'root'; // Standardmäßig 'root' in XAMPP, aber ggf. anpassen, wenn ein Passwort gesetzt ist
$password = ''; // Leer lassen, falls kein Passwort gesetzt ist

$dsn = "mysql:host=$host;dbname=$db;port=3307;charset=utf8mb4"; // Port 3307 beachten

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}
