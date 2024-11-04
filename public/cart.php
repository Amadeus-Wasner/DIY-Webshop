<?php
session_start();
require_once '../config/database.php';

// Stelle sicher, dass ein Warenkorb existiert
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Produkt zum Warenkorb hinzufügen
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

    // Prüfen, ob das Produkt bereits im Warenkorb ist
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: cart.php");
    exit;
}

// Produkt aus dem Warenkorb entfernen
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

// Warenkorb leeren
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

// Produktinformationen laden
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($product_ids)");
    $cart_items = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb</title>
</head>
<body>
    <h1>Warenkorb</h1>

    <?php if (empty($cart_items)): ?>
        <p>Dein Warenkorb ist leer.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Produkt</th>
                <th>Preis</th>
                <th>Menge</th>
                <th>Gesamt</th>
                <th>Aktionen</th>
            </tr>
            <?php $total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <?php
                $quantity = $_SESSION['cart'][$item['id']];
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?> €</td>
                    <td><?= $quantity ?></td>
                    <td><?= $subtotal ?> €</td>
                    <td>
                        <a href="cart.php?action=remove&id=<?= $item['id'] ?>">Entfernen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Gesamt:</td>
                <td><?= $total ?> €</td>
                <td><a href="cart.php?action=clear">Warenkorb leeren</a></td>
            </tr>
        </table>
        <a href="checkout.php">Zur Kasse</a>
    <?php endif; ?>
</body>
</html>
