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
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../layouts/headimport.php'; ?> <!-- CSS/Bootstrap-Imports -->
</head>
<body>
    <?php include '../layouts/header.php'; ?> <!-- Header -->

    <main class="container mt-4">
        <h1>Willkommen im Dashboard!</h1>
        <p>Sie sind eingeloggt als: <strong><?= htmlspecialchars($_SESSION['user_email']); ?></strong></p>

        <!-- Anzeige der Online-Benutzer -->
        <p>Online-Benutzer: <span id="online-users-count">Laden...</span></p>

        <!-- Buttons für Navigation -->
        <div class="mt-4">
            <a href="artikel.php" class="btn btn-primary">Artikel anzeigen</a>
            <a href="profile.php" class="btn btn-secondary">Mein Profil</a>
            <a href="../controllers/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </main>

    <?php include '../layouts/footer.php'; ?> <!-- Footer -->
</body>
</html>
