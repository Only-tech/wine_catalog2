<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Vin : L’art de savourer le temps</title>
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Allison&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <a href="index.php" class="website-name"><span>🍇🍷 </span>L’art de savourer le temps</a>
        <nav class="menu">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li>
                    <a href="cart.php" class="cart"><img src="assets/images/cart-logo.svg" alt="cart"><span id="cart-item-count" class="cart-count"><?php echo isset($cart_item_count) ? $cart_item_count : 0; ?></span></a>
                </li>
            </ul>
        </nav>

    </header>