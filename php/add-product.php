<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO wines (name, price, image, description) VALUES (:name, :price, :image, :description);";
    $query = $db->prepare($sql);

    $query->execute([$_POST['name'], $_POST['price'], $_POST['image'], $_POST['description']]);
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Catalogue de Vin</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
</head>

<body class="body-pink">

    <main>
        <div class="add-product">
            <h2>Ajouter un nouveau produit</h2>
            <form action="add-product.php" method="post">
                <label for="name">Produit :</label>
                <input type="text" name="name" required>
                <label for="price">Prix :</label>
                <input type="number" name="price" step="0.01" required>
                <label for="image">Image :</label>
                <input type="text" name="image" required>
                <label for="description">Description :</label>
                <textarea name="description"></textarea>
                <button class="button-add-product" type="submit">Ajouter</button>
            </form>
            <a class="button-back" href="admin.php">Retour</a>
        </div>
    </main>
    <button id="changeBackground" data-title="Changer le fond d'écran"><img src="assets/images/bg-changeBtn.svg" alt="Changer le fond d'écran"></button>
    <script src="assets/js/changeBg.js"></script>
</body>

</html>