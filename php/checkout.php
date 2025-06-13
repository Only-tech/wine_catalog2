<?php
session_start();
include 'header.php';

if (!empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Vide le panier après achat
    echo "<article>
            <h2>Commande validée !</h2>
            <p>Merci pour votre achat. Votre commande sera bientôt traitée.</p>
        </article>";
} else {
    echo "<article><h2>Aucune commande en cours !</h2></article>";
}
?>

<a href="index.php" class="button-back-to-catalog">Retour au catalogue</a>

<?php include 'footer.php'; ?>