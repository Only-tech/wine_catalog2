<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Vin : L’art de savourer le temps</title>
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <button id="burgerBtn" class="burger-button">☰</button>
        <a href="index.php" class="website-name" title="L'art de savourer le temps : un vin, une histoire"><span>🍇🍷 </span>L’art de savourer le temps</a>

        <form action="index.php" method="get" class="search-form">
            <input type="search" name="search" placeholder="Rechercher un vin 🍷🍾"
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit" class="search-button">
                <svg width="2rem" height="2rem" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <title>Rechercher</title>
                    <path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
            </button>
            <?php
            // Affiche un bouton "X" pour effacer la recherche si un terme est déjà présent
            if (isset($_GET['search']) && $_GET['search'] !== ''):
            ?>
                <a href="index.php" class="clear-search-button" title="Effacer la recherche">+</a>
            <?php endif; ?>
        </form>

        <nav class="menu">
            <ul class="mobile-menu">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#promo">Promo</a></li>
            </ul>
            <a href="cart.php" class="cart"><img src="assets/images/cart-logo.svg" alt="cart" title="Panier"><span id="cart-item-count" class="cart-count"><?php echo isset($cart_item_count) ? $cart_item_count : 0; ?></span></a>
        </nav>

    </header>