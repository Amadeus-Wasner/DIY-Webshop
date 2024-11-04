<?php
require_once '../config/database.php';
session_start();

// Produkte aus der Datenbank abrufen
$stmt = $pdo->query("SELECT * FROM products WHERE stock > 0");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Produkte</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Startseite</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Abmelden</a>
            <?php else: ?>
                <a href="login.php">Anmelden</a>
                <a href="register.php">Registrieren</a>
            <?php endif; ?>
            <a href="products.php">Produkte</a>
            <a href="cart.php">Warenkorb</a>
        </nav>
    </header>

    <main>
        <h1>Unsere Produkte</h1>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Preis: <?= htmlspecialchars($product['price']) ?> €</p>
                    <p>Verfügbar: <?= htmlspecialchars($product['stock']) ?></p>
                    <?php if ($product['image']): ?>
                        <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php endif; ?>
                    <a href="cart.php?action=add&id=<?= $product['id'] ?>">In den Warenkorb</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
