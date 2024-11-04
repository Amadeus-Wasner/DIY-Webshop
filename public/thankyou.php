<?php
session_start();

if (!isset($_SESSION['order'])) {
    header("Location: index.php");
    exit;
}

// Bestelldaten anzeigen
$order = $_SESSION['order'];
unset($_SESSION['order']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Vielen Dank für Ihre Bestellung</title>
</head>
<body>
    <h1>Vielen Dank für Ihre Bestellung!</h1>
    <p>Ihre Bestellung über <?= $order['total'] ?> € wurde erfolgreich abgeschlossen.</p>
    <h2>Lieferadresse:</h2>
    <p><?= htmlspecialchars($order['name']) ?></p>
    <p><?= htmlspecialchars($order['address']) ?></p>
    <p>Zahlungsmethode: <?= htmlspecialchars($order['payment']) ?></p>

    <a href="products.php">Zurück zu den Produkten</a>
</body>
</html>
