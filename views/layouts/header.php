<?php
session_start(); // Session starten
require_once '../config/db_connection.php'; // Datenbankverbindung einbinden

// Punkte des eingeloggten Benutzers abrufen
$punkte = 0; // Standardwert für nicht eingeloggte Benutzer
if (isset($_SESSION['user_id'])) {
    try {
        $pdo = getDBConnection();
        $punkteStmt = $pdo->prepare('SELECT Punkte FROM Punkte WHERE KundenID = ?');
        $punkteStmt->execute([$_SESSION['user_id']]);
        $punkte = $punkteStmt->fetchColumn() ?? 0; // Standardwert 0, falls keine Punkte vorhanden
    } catch (PDOException $e) {
        $punkte = 0; // Bei Fehlern Punkte auf 0 setzen
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIY Webshop</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../views/home.php">DIY Webshop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="../views/home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../views/products.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="../views/cart_view.php">Warenkorb</a></li>
                    
                    <!-- Punkteanzeige für eingeloggte Benutzer -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text text-white">
                                Punkte: <?= htmlspecialchars($punkte) ?>
                            </span>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="../include/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="../views/login_view.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
