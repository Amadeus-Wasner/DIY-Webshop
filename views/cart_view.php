<?php
// Warenkorb-Logik einbinden
include '../controllers/CartController.php';
$cartItems = getCartItems(); // Funktion aus CartController
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb</title>
    <?php include '../views/layouts/headimport.php'; ?>
</head>
<body>
    <?php include '../views/layouts/header.php'; ?>
    
    <main class="container mt-4">
        <h1>Warenkorb</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Produkt</th>
                <th>Anzahl</th>
                <th>Preis</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?> €</td>
                    <td>
                        <a href="../controllers/remove_from_cart.php?cart_item_id=<?php echo $item['id']; ?>" class="btn btn-danger">Entfernen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a href="checkout_view.php" class="btn btn-success">Zur Kasse</a>
    </main>
    
    <?php include '../views/layouts/footer.php'; ?>
</body>
</html>
