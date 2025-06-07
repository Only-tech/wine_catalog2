<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $sql = "UPDATE wines SET name = :name, price = :price, image = :image, description = :description WHERE id = :id";
    $query = $db->prepare($sql);
    $query->execute([$_POST['name'], $_POST['price'], $_POST['image'], $_POST['description'], $_POST['id']]);
    header("Location: admin.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM wines WHERE id = :id";
$query = $db->prepare($sql);
$query->execute([$id]);
$wine = $query->fetch(PDO::FETCH_ASSOC);

// print_r($wine);

require("disconnect.php");
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

<body>
    <main>
        <div class="update-product">
            <h2>Modifier un produit</h2>
            <form action="update-product.php" method="post">
                <input type="hidden" name="id" value="<?php echo $wine['id']; ?>">
                <label for="name">Produit :</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($wine['name']); ?>" required>
                <label for="price">Prix :</label>
                <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($wine['price']); ?>" required>
                <label for="image">Image :</label>
                <input type="text" name="image" value="<?php echo htmlspecialchars($wine['image']); ?>" required>
                <label for="description">Description :</label>
                <textarea name="description"><?php echo htmlspecialchars($wine['description']); ?></textarea>
                <button class="button-update-product" type="submit">Modifier</button>
            </form>
            <a class="button-back" href="admin.php">Retour</a>
        </div>
    </main>

</body>

</html>