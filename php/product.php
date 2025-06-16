<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Vérifie si l'ID est présent dans l'URL et qu'il n'est pas vide.
    // (Le 'isset' est facultatif car 'empty' gère déjà l'absence de la variable).

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

    $id = $_GET['id'];
    // print_r($id);

    $sql = "SELECT * FROM wines WHERE id= :id";

    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);

    $query->execute();

    $wine = $query->fetch();

    // print_r($wine);

    require("disconnect.php");
}
?>

<?php
if (!$wine) {
    echo "<p>Produit non trouvé.</p>";
    include 'footer.php';
    exit;
}
?>

<main>
    <section>
        <div class="product-card">
            <h2><?php echo htmlspecialchars($wine['name']); ?></h2>
            <div class="product-detail">
                <div class="wine-actions">
                    <img src="assets/images/<?php echo htmlspecialchars($wine['image']); ?>" alt="<?php echo htmlspecialchars($wine['name']); ?>">
                    <p><?php echo htmlspecialchars($wine['price']); ?> €</p>
                    <form action="add-to-cart.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="id" value="<?php echo $wine['id']; ?>">
                        <div class="quantity-selector">
                            <div class="quantity-display" onclick="toggleMenu(this)">
                                <span class="selected-quantity">1</span>
                                <span class="arrow"></span>
                            </div>
                            <input type="hidden" name="quantity" class="hidden-quantity-input" value="1">
                            <div class="quantity-list" id="quantity-menu-<?php echo $wine['id']; ?>">
                                <button type="button" onclick="selectQuantity(1, this)">1</button>
                                <button type="button" onclick="selectQuantity(2, this)">2</button>
                                <button type="button" onclick="selectQuantity(3, this)">3</button>
                                <button type="button" onclick="selectQuantity(6, this)">6</button>
                                <button type="button" onclick="selectQuantity(12, this)">12</button>
                                <div class="quantity-input-container">
                                    <input type="number" id="other-quantity-input-<?php echo $wine['id']; ?>"
                                        placeholder="Autre" min="1" onchange="selectQuantity(this.value, this)">
                                </div>
                            </div>
                            <button type="submit" class="button-add-to-cart">AJOUTER</button>
                        </div>
                    </form>
                </div>
                <div>
                    <p>
                        <?php
                        // Convert **text** and \n to <strong>text</strong> and <br>
                        $formatted_description = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $wine['description']);
                        $formatted_description = preg_replace('/\\\n/', '<br>', $formatted_description);
                        // Sanitize the final output for security
                        echo strip_tags($formatted_description, '<br><strong><b>');
                        ?>
                    </p>
                    <p><strong>Ce que nos clients pensent de ce produit 👇</strong><br></p>
                    <p><br><?php echo htmlspecialchars($wine['avis']); ?></p>
                </div>

            </div>

        </div>
        <a href="index.php" class="button-back-to-catalog">Retour au catalogue</a>
    </section>

    <section id="addToCartModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 class="modal-message"></h3>
            <div class="modal-item-details">
            </div>
            <div class="modal-buttons">
                <button class="button-remove" id="viewCartBtn">Voir mon panier</button>
                <button class="button-cancel" id="continueShoppingBtn">Continuer mes achats</button>
            </div>
        </div>
    </section>
</main>

<script src="assets/js/dropdown.js"></script>
<script src="assets/js/product.js"></script>

<?php include 'footer.php'; ?>