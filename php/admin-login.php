<?php
session_start();

$password = 'WineCatalog2025!';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $errorMessage = 'Mot de passe incorrect.';
    }
}

// Calcule le nombre total de produits dans le panier (pour le header)
$cart_item_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_item_count += $item['quantity'];
    }
}

include 'header.php';
?>

<main>
    <section class="admin-login">
        <h3 class="admin-acces">Accès Administrateur</h3>
        <?php if ($errorMessage): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form action="admin-login.php" method="post" class="admin-acces-form">
            <!-- <label for="password"></label> -->
            <input type="password" id="password" name="password" placeholder="Entrer le mot de passer" required>
            <button type="submit" class="button">Se connecter</button>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>