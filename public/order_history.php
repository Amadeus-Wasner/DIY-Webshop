<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Bestellungen des Benutzers abrufen
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellhistorie</title>
</head>
<body>
    <h1>Deine Bestellhistorie</h1>
    <?php if (empty($orders)): ?>
        <p>Du hast noch keine Bestellungen.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h2>Bestellung Nr. <?= $order['id'] ?></h2>
                <p>Datum: <?= $order['order_date'] ?></p>
                <p>Gesamtbetrag: <?= $order['total'] ?> €</p>
                <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                <h3>Produkte:</h3>
                <ul>
                    <?php
                    // Produkte für die aktuelle Bestellung abrufen
                    $stmt = $pdo->prepare("SELECT * FROM order_items INNER JOIN products ON order_items.product_id = products.id WHERE order_id = ?");
                    $stmt->execute([$order['id']]);
                    $items = $stmt->fetchAll();
                    ?>
                    <?php foreach ($items as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> - <?= $item['quantity'] ?> Stück - <?= $item['price'] ?> €</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
