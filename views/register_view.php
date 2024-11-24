<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
    <?php include '../layouts/headimport.php'; ?> <!-- Falls Bootstrap oder CSS eingebunden ist -->
</head>
<body>
    <?php include '../layouts/header.php'; ?> <!-- Header -->

    <main class="container mt-4">
        <h1>Registrieren</h1>
        <form action="../controllers/register.php" method="post">
            <!-- E-Mail -->
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <!-- Passwort -->
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Registrieren</button>
        </form>
    </main>

    <?php include '../layouts/footer.php'; ?> <!-- Footer -->
</body>
</html>
