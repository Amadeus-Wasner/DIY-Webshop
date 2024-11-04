<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$product_ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($product_ids)");
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $item) {
    $quantity = $_SESSION['cart'][$item['id']];
    $subtotal = $item['price'] * $quantity;
    $total += $subtotal;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $user_id = $_SESSION['user_id'] ?? null; // Benutzer muss eingeloggt sein

    // Bestellung in der `orders`-Tabelle speichern
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, name, address, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $total, $name, $address, $payment]);
    $order_id = $pdo->lastInsertId();

    // Einzelne Produkte in der `order_items`-Tabelle speichern
    foreach ($cart_items as $item) {
        $quantity = $_SESSION['cart'][$item['id']];
        $price = $item['price'];
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $quantity, $price]);
    }

    // Warenkorb leeren
    $_SESSION['cart'] = [];
    $_SESSION['order_id'] = $order_id; // Zum Anzeigen auf der Dankesseite

    header("Location: thankyou.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Zur Kasse</h1>
    <h2>Warenkorbübersicht</h2>
    <ul>
        <?php foreach ($cart_items as $item): ?>
            <li>
                <?= htmlspecialchars($item['name']) ?> - <?= $_SESSION['cart'][$item['id']] ?> Stück - <?= $item['price'] * $_SESSION['cart'][$item['id']] ?> €
            </li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Gesamtbetrag: <?= $total ?> €</strong></p>

    <h2>Liefer- und Zahlungsinformationen</h2>
    <form method="post" action="checkout.php">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        
        <label for="address">Adresse:</label>
        <input type="text" name="address" id="address" required>
        
        <label for="payment">Zahlungsmethode:</label>
        <select name="payment" id="payment" required>
            <option value="paypal">PayPal</option>
            <option value="credit_card">Kreditkarte</option>
        </select>
        
        <button type="submit">Bestellung abschließen</button>
    </form>
</body>
</html>
