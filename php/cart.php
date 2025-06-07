<?php
session_start(); // Assure que la session est démarrée avant d'accéder à $_SESSION

require_once 'connect.php';

// Calcule le nombre total de produits dans le panier
$cart_item_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_item_count += $item['quantity'];
    }
}

include 'header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalCartPrice = 0; // fait le total des prix dans le panier

// Récupère la modification du total dans le panier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity']) && isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = (int)$_POST['id'];
    $newQuantity = (int)$_POST['quantity'];

    if ($newQuantity < 1) {
        $newQuantity = 1; // assure que la quantité de produits dans le panier est au moins 1
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $newQuantity;
    }
}

// Ré-calcule le nombre total de produits dans le panier après chaque modification
foreach ($cart as $id => $item) {
    $itemSubtotal = $item['price'] * $item['quantity'];
    $totalCartPrice += $itemSubtotal;
}
?>


<main>
    <section class="cart-actions">
        <h2>Mon panier</h2>

        <?php if (empty($cart)): ?>
            <p>Votre panier est vide !</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item):
                        $itemSubtotal = $item['price'] * $item['quantity'];
                    ?>
                        <tr>
                            <td><img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" width="50"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 2)); ?> €</td>
                            <td>
                                <form action="cart.php" method="post" class="quantity-update-form">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1"
                                        class="quantity-input" onchange="this.form.submit()">
                                    <input type="hidden" name="update_quantity" value="1">
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars(number_format($itemSubtotal, 2)); ?> €</td>
                            <td>
                                <form class="delete-item-form" method="post">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                    <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($item['price']); ?>">
                                    <input type="hidden" name="item_image" value="<?php echo htmlspecialchars($item['image']); ?>">

                                    <button type="submit" class="button-remove">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="total-label">Total du panier</td>
                        <td><strong><?php echo htmlspecialchars(number_format($totalCartPrice, 2)); ?> €</strong></td>
                        <td>
                            <a href="checkout.php" class="button-checkout">Finaliser la commande</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>


        <a href="index.php" class="button-back-to-catalog">Retour au catalogue</a>

    </section>

    <section id="deleteConfirmationModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Confirmer la suppression</h3>
            <p>Êtes-vous sûr de vouloir supprimer ce produit de votre panier ?</p>
            <div class="modal-item-details">
            </div>
            <div class="modal-buttons">
                <button id="confirmDeleteButton" class="button-remove">Supprimer</button>
                <button id="cancelDeleteButton" class="button-cancel">Annuler</button>
            </div>
        </div>
    </section>
</main>

<script src="assets/js/cart.js"></script>

<?php include 'footer.php'; ?>