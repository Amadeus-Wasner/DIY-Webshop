<?php
// Session starten und überprüfen, ob der Benutzer eingeloggt ist
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_view.php'); // Weiterleitung zum Login
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <title>Profil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../layouts/headimport.php'; ?> <!-- CSS und Bootstrap-Imports -->
</head>
<body>
    <?php include '../layouts/header.php'; ?> <!-- Header -->

    <main class="container mt-4">
        <h1>Mein Profil</h1>

        <!-- Passwort-Änderungsformular -->
        <form id="changePasswordForm" action="../controllers/change_password.php" method="post">
            <div class="form-group">
                <label for="new_password">Neues Passwort:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Passwort bestätigen:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Passwort ändern</button>
        </form>
    </main>

    <?php include '../layouts/footer.php'; ?> <!-- Footer -->
</body>
</html>
