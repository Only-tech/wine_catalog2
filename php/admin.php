<?php
session_start(); // Assure que la session est démarrée avant d'accéder à $_SESSION

// Verifie si admin est connecté, si non renvoit vers la page de connexion
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit;
}

require_once 'connect.php';

// Calcule le nombre total de produits dans le panier
$cart_item_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_item_count += $item['quantity'];
    }
}

include 'header.php';

// $stmt = $pdo->query('SELECT * FROM wines ORDER BY id');
$sql = "SELECT * FROM wines ORDER BY id";

// Préparation de la requête
$query = $db->prepare($sql);

// Exécution de la requête
$query->execute();

// Récupération des données de la requête
$wines = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="admin-actions">
        <h2>Gestion des produits</h2>
        <a href="add-product.php" class="button-add-product">Ajouter un produit</a>
        <table>
            <tr>
                <th>Identifiant</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
            <?php foreach ($wines as $wine): ?>
                <tr>
                    <td><?php echo htmlspecialchars($wine['id']); ?></td>
                    <td><?php echo htmlspecialchars($wine['name']); ?></td>
                    <td><?php echo htmlspecialchars($wine['price']); ?> €</td>
                    <td class="button-update-delete">
                        <a href="update-product.php?id=<?php echo $wine['id']; ?>" class="button-update">Modifier</a> |
                        <form class="delete-item-form" method="post">
                            <input type="hidden" name="id" value="<?php echo $wine['id']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($wine['name']); ?>">
                            <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($wine['price']); ?>">
                            <input type="hidden" name="item_image" value="<?php echo htmlspecialchars($wine['image']); ?>">

                            <button type="submit" class="button-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Confirmer la suppression</h3>
            <div class="modal-item-details">
            </div>
            <div class="modal-buttons">
                <button id="confirmDeleteButton" class="button-remove">Supprimer</button>
                <button id="cancelDeleteButton" class="button-cancel">Annuler</button>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/admin.js"></script>

<?php include 'footer.php'; ?>