<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <?php include '../views/layouts/headimport.php'; ?>
</head>
<body>
    <?php include '../views/layouts/header.php'; ?>
    
    <main class="container mt-4">
        <h1>Checkout</h1>
        <form action="../controllers/CheckoutController.php" method="post">
            <div class="form-group">
                <label for="address">Lieferadresse:</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="payment">Zahlungsmethode:</label>
                <select id="payment" name="payment" class="form-control">
                    <option value="credit_card">Kreditkarte</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Banküberweisung</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Bestellung abschließen</button>
        </form>
    </main>
    
    <?php include '../views/layouts/footer.php'; ?>
</body>
</html>
