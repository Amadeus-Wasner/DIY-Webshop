<?php
// Session starten und überprüfen, ob der Benutzer eingeloggt ist
session_start();
if (!isset($_SESSION['user_id'])) {
    // Weiterleitung zum Login, wenn der Benutzer nicht eingeloggt ist
    header('Location: login_view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Artikel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../layouts/headimport.php'; ?> <!-- CSS/Bootstrap-Imports -->
</head>
<body>
    <?php include '../layouts/header.php'; ?> <!-- Header -->

    <main class="container mt-4">
        <h1>Artikel</h1>
        <p>Hier können Sie alle verfügbaren Artikel sehen.</p>

        <!-- Artikel als Platzhalter -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Artikel 1</h5>
                        <p class="card-text">Beschreibung von Artikel 1.</p>
                        <a href="#" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Artikel 2</h5>
                        <p class="card-text">Beschreibung von Artikel 2.</p>
                        <a href="#" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Artikel 3</h5>
                        <p class="card-text">Beschreibung von Artikel 3.</p>
                        <a href="#" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../layouts/footer.php'; ?> <!-- Footer -->
</body>
</html>
