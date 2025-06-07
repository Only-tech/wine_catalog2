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

// --- Début du code PHP pour la barre de recherche ---
$sql = "SELECT * FROM wines";
$params = []; // Tableau pour stocker les paramètres de la requête préparée

// Vérifie si un terme de recherche est fourni via l'URL (méthode GET)
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $searchTerm = '%' . $_GET['search'] . '%'; // Ajoute des jokers pour la recherche LIKE
    // Recherche insensible à la casse sur le nom OU la description
    // Utilisation de ILIKE pour PostgreSQL (si utilisation de MySQL, ce serait LIKE)
    $sql .= " WHERE name ILIKE :search OR description ILIKE :search";
    $params[':search'] = $searchTerm;
}

$sql .= " ORDER BY id"; // Tri toujours par ID

// Préparation et exécution de la requête
$query = $db->prepare($sql);
$query->execute($params);
$wines = $query->fetchAll(PDO::FETCH_ASSOC);

require("disconnect.php");
?>

<main>
    <section class="catalogue-container">
        <h3>Chaque vin a une histoire, une saveur unique à explorer</h3>
        <h5>Plongez dans notre sélection et laissez-vous surprendre par des merveilles gustatives</h5>

        <div class="search-bar-container">
            <form action="index.php" method="get" class="search-form">
                <input type="search" name="search" placeholder="Rechercher un vin 🍷🍾"
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="search-button">Rechercher</button>
                <?php
                // Affiche un bouton "X" pour effacer la recherche si un terme est déjà présent
                if (isset($_GET['search']) && $_GET['search'] !== ''):
                ?>
                    <a href="index.php" class="clear-search-button">X</a>
                <?php endif; ?>
            </form>
        </div>
        <div class="catalogue">
            <?php
            // --- Affichage des résultats (ou du message d'absence de résultats) ---
            if (empty($wines)):
            ?>
                <figure class="no-results-message">
                    <img src="../assets/images/man-no-results-message.png" alt="Image pas de résultats">
                    <figcaption>
                        <p>Aucun vin ne correspond à votre recherche !</p>
                    </figcaption>
                </figure>
            <?php else: ?>
                <?php foreach ($wines as $wine): ?>
                    <div class="expandable-wine-card">
                        <div class="wine-card" onmouseover="playSound()">
                            <img src="assets/images/<?php echo htmlspecialchars($wine['image']); ?>" alt="<?php echo htmlspecialchars($wine['name']); ?>">
                            <div class="wine-info">
                                <h3><?php echo htmlspecialchars($wine['name']); ?></h3>
                                <p><?php echo htmlspecialchars($wine['price']); ?> €</p>
                                <div class="wine-actions">
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
                            </div>
                        </div>
                        <a href="product.php?id=<?php echo $wine['id']; ?>" class="hidden-wine-button">
                            <p>Voir le produit - description</p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <audio id="scroll-sound">
        <source src="assets/sounds/scroll.mp3" type="audio/mpeg">
    </audio>

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
<script src="assets/js/index.js"></script>

<?php include 'footer.php'; ?>