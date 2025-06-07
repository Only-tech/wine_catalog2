<?php
session_start();
include 'header.php';

if (!empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Vide le panier après achat
    echo "<h2>Commande validée !</h2>";
    echo "<p>Merci pour votre achat. Votre commande sera bientôt traitée.</p>";
} else {
    echo "<h2>Aucune commande en cours.</h2>";
}
?>

<a href="index.php">Retour au catalogue</a>

<?php include 'footer.php'; ?>