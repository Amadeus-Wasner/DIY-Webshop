<?php
session_start();
require_once '../config/db_connection.php'; // Datenbankverbindung
require_once '../models/send_email.php'; // E-Mail-Funktion
require_once '../controllers/logged_in.php'; // Prüfen, ob der Benutzer eingeloggt ist

function calculateDiscount($price, $quantity) {
    if ($quantity >= 10) {
        $discountRate = 0.20;
    } elseif ($quantity >= 5) {
        $discountRate = 0.10;
    } else {
        $discountRate = 0.00;
    }

    $discountAmount = $price * $quantity * $discountRate;
    return [$discountAmount, $discountRate];
}

$userId = $_SESSION['users_id'];

// Warenkorb-Artikel abrufen
$stmt = $link->prepare("SELECT p.id, p.name, p.price, cb.quantity, cb.rabatt, (p.price * cb.quantity) AS product_total 
                        FROM `cart-body` cb
                        JOIN `cart-header` ch ON cb.warenkorb_id = ch.id
                        JOIN `products` p ON cb.product_id = p.id
                        WHERE ch.users_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$totalPrice = 0;
$totalDiscount = 0;
while ($row = $result->fetch_assoc()) {
    $productId = $row['id'];
    $price = $row['price'];
    $quantity = $row['quantity'];
    $productTotal = $row['product_total'];
    $discountRate = $row['rabatt'] / 100;

    $discountAmount = $price * $quantity * $discountRate;
    $discountDisplay = number_format($discountAmount, 2) . '€ (' . ($discountRate * 100) . '%)';
    $productTotalAfterDiscount = $productTotal - $discountAmount;

    $cartItems[] = [
        'id' => $productId,
        'name' => $row['name'],
        'price' => $price,
        'quantity' => $quantity,
        'product_total' => $productTotalAfterDiscount,
        'discount' => $discountDisplay
    ];

    $totalPrice += $productTotalAfterDiscount;
    $totalDiscount += $discountAmount;
}

$stmt->close();

// Benutzerpunkte abrufen
$pointsStmt = $link->prepare("SELECT points, is_active FROM points WHERE users_id = ?");
$pointsStmt->bind_param("i", $userId);
$pointsStmt->execute();
$pointsResult = $pointsStmt->get_result();
$pointsData = $pointsResult->fetch_assoc();
$pointsStmt->close();

$userPoints = $pointsData['points'];
$pointsActive = $pointsData['is_active'];
$pointsValue = $pointsActive ? $userPoints / 1000 : 0;

$totalPriceAfterPoints = $totalPrice - $pointsValue; // Punkte vom Gesamtpreis abziehen

// Bestellung abschließen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $shippingMethod = $_POST['shippingMethod'];
    $paymentMethod = $_POST['paymentMethod'];
    $nameOnCard = $_POST['nameOnCard'];
    $cardNumber = $_POST['cardNumber'];
    $expiration = $_POST['expiration'];
    $cvv = $_POST['cvv'];

    // Versandkosten berechnen
    $shippingCost = 0;
    if ($shippingMethod === 'DHL') {
        $shippingCost = 4.5;
    } elseif ($shippingMethod === 'DHL Express') {
        $shippingCost = 10.5;
    } elseif ($shippingMethod === 'LPD') {
        $shippingCost = 7.5;
    }

    $totalAmount = $totalPriceAfterPoints + $shippingCost;

    // Bestellung speichern
    $stmt = $link->prepare("INSERT INTO orders (users_id, order_date, total_amount, shipping_method, is_express_shipping, is_paid) VALUES (?, NOW(), ?, ?, ?, ?)");
    $stmt->bind_param("idssi", $userId, $totalAmount, $shippingMethod, $isExpressShipping, $isPaid);

    $isExpressShipping = ($shippingMethod === 'DHL Express') ? 1 : 0;
    $isPaid = 1;

    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    // Artikel zur Bestellung hinzufügen
    $stmt = $link->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    // Warenkorb leeren
    $stmt = $link->prepare("DELETE FROM `cart-body` WHERE warenkorb_id = (SELECT id FROM `cart-header` WHERE users_id = ?)");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Benutzerpunkte zurücksetzen und neue Punkte hinzufügen
    $stmt = $link->prepare("UPDATE points SET points = 25 WHERE users_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Bestätigungs-E-Mail senden
    $emailTemplate = getPaymentConfirmationEmail($firstName, $cartItems, $totalPriceAfterPoints, $shippingMethod, $shippingCost, $totalDiscount);
    sendEmail($email, $firstName, $emailTemplate);

    header("Location: danke.php");
    exit();
}

$link->close();
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <?php include '../views/layouts/headimport.php'; ?>
</head>
<body>
<?php include '../views/layouts/header.php'; ?>
<div class="container mt-5 checkout-container">
    <div class="row g-5">
        <div class="col-md-7 col-lg-8">
            <h4 class="mb-3 checkout-header">Rechnungsadresse</h4>
            <form class="needs-validation" novalidate method="POST">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label">Vorname</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                        <div class="invalid-feedback">
                            Bitte geben Sie einen gültigen Vornamen ein.
                        </div>
                    </div>
                    <!-- Weitere Formularfelder -->
                </div>
                <hr class="my-4">
                <button class="w-100 btn btn-primary btn-lg" type="submit">Weiter zur Bestellung</button>
            </form>
        </div>
    </div>
</div>
<?php include '../views/layouts/footer.php'; ?>
<script>
    // Script zur Aktualisierung der Versandkosten
</script>
</body>
</html>
