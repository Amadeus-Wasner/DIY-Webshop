<?php
function getDBConnection() {
    $host = '127.0.0.1'; // Oder 'localhost'
    $dbname = 'db_webshop'; // Name der Datenbank
    $username = 'root'; // MySQL-Benutzername (Standard in XAMPP: 'root')
    $password = ''; // Passwort (Standard in XAMPP: leer)

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
    }
}
?>
