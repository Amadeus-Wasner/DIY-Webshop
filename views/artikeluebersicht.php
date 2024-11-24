<?php
session_start();
require_once '../config/db_connection.php'; // Verbindung zur Datenbank
require_once '../controllers/add_to_cart.php'; // Include der Funktion für den Warenkorb

// Überprüfen, ob ein Suchbegriff eingegeben wurde
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
}

// Produkte aus der Datenbank abrufen
if ($searchTerm) {
    $stmt = $link->prepare("SELECT * FROM products WHERE name LIKE ?");
    $searchTermWithWildcards = "%$searchTerm%";
    $stmt->bind_param("s", $searchTermWithWildcards);
} else {
    $stmt = $link->prepare("SELECT * FROM products");
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();

// Hinzufügen zum Warenkorb
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['users_id'])) {
    $userId = $_SESSION['users_id'];
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addToCart($userId, $productId, $quantity, $link);
    header("Location: cart_view.php"); // Verweis auf cart_view.php
    exit();
}

$link->close();
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikelübersicht</title>
    <?php include '../layouts/headimport.php'; ?> <!-- Pfad angepasst -->
</head>
<body>
<?php include '../layouts/header.php'; ?> <!-- Header eingebunden -->
<div class="container mt-5">
    <h1 class="mb-4">Artikelübersicht</h1>

    <!-- Suchformular -->
    <form id="search-form" method="get" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" id="search-input" class="form-control" placeholder="Produkte suchen..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-primary">Suchen</button>
        </div>
    </form>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="product_details.php?id=<?php echo htmlspecialchars($product['id']); ?>"> <!-- Pfad zu product_details.php -->
                            <img src="../assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="Produktbild">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['price']); ?> €</p>
                            <?php if (isset($_SESSION['users_id'])): ?>
                                <form method="post" action="">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                    <div class="d-flex">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control me-2" style="width: 80px;">
                                        <button type="submit" class="btn btn-primary">In den Warenkorb</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Keine Produkte gefunden.</p>
        <?php endif; ?>
    </div>
</div>
<?php include '../layouts/footer.php'; ?> <!-- Footer eingebunden -->

<script>
    let searchTimeout;
    document.getElementById('search-input').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            document.getElementById('search-form').submit();
        }, 500); // Verzögerung von 500 ms
    });
</script>
</body>
</html>
