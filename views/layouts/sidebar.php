<aside class="sidebar bg-light p-3">
    <h5 class="mb-4">Produktkategorien</h5>
    <ul class="list-unstyled">
        <li><a href="../views/category.php?category=kerzen" class="text-decoration-none">Kerzen</a></li>
        <li><a href="../views/category.php?category=gartenarbeit" class="text-decoration-none">Gartenarbeit</a></li>
        <li><a href="../views/category.php?category=handwerk" class="text-decoration-none">Handwerk</a></li>
        <li><a href="../views/category.php?category=deko" class="text-decoration-none">Deko</a></li>
    </ul>
    <hr>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <h5 class="mt-4">Mein Konto</h5>
        <ul class="list-unstyled">
            <li><a href="../views/account.php" class="text-decoration-none">Mein Profil</a></li>
            <li><a href="../views/orders.php" class="text-decoration-none">Meine Bestellungen</a></li>
            <li><a href="../include/logout.php" class="text-decoration-none">Abmelden</a></li>
        </ul>
    <?php else: ?>
        <h5 class="mt-4">Konto</h5>
        <ul class="list-unstyled">
            <li><a href="../views/login_view.php" class="text-decoration-none">Anmelden</a></li>
            <li><a href="../views/register_view.php" class="text-decoration-none">Registrieren</a></li>
        </ul>
    <?php endif; ?>
</aside>
