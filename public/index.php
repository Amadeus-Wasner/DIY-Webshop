<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Webshop - Startseite</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Startseite</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Willkommen, Benutzer!</span>
                <a href="logout.php">Abmelden</a>
            <?php else: ?>
                <a href="login.php">Anmelden</a>
                <a href="register.php">Registrieren</a>
            <?php endif; ?>
            <a href="products.php">Unsere Produkte ansehen</a>
        </nav>
    </header>

    <main>
        <h1>Willkommen auf der Startseite des Webshops!</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Du bist eingeloggt.</p>
        <?php else: ?>
            <p>Bitte melde dich an oder registriere dich, um den Webshop zu nutzen.</p>
        <?php endif; ?>
        <a href="products.php">Unsere Produkte ansehen</a>
    </main>
</body>
</html>
