<?php
session_start();
require_once 'connect.php'; // Inclut le fichier de connexion à la base de données

// Détecte si la requête est une requête AJAX. Important pour savoir si on doit renvoyer du JSON ou rediriger.
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Vérifie si la requête est de type POST et si l'ID du produit est défini
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id']; // Récupère et convertit l'ID du produit en entier pour des raisons de sécurité

    // Récupère la quantité envoyée par le formulaire. Par défaut à 1 si non trouvée ou invalide.
    $selected_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Assure que la quantité sélectionnée est au minimum 1 pour éviter les quantités négatives ou nulles
    if ($selected_quantity < 1) {
        $selected_quantity = 1;
    }

    // Prépare et exécute une requête pour récupérer les détails du vin depuis la base de données
    $sql = "SELECT * FROM wines WHERE id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT); // Lie la valeur de l'ID en tant qu'entier
    $query->execute();
    $wine = $query->fetch(PDO::FETCH_ASSOC); // Récupère les détails du vin sous forme de tableau associatif

    // Si le vin existe dans la base de données
    if ($wine) {
        // Vérifie si le produit est déjà dans le panier de session
        if (isset($_SESSION['cart'][$id])) {
            // Si oui, ajoute la quantité sélectionnée à la quantité existante dans le panier
            $_SESSION['cart'][$id]['quantity'] += $selected_quantity;
        } else {
            // Si non, ajoute le produit au panier avec la quantité sélectionnée
            $_SESSION['cart'][$id] = [
                'name' => $wine['name'],
                'price' => $wine['price'],
                'image' => $wine['image'],
                'quantity' => $selected_quantity // Utilise la quantité sélectionnée par l'utilisateur
            ];
        }
    }

    // Calcule le nombre total de produits dans le panier après modification
    // Ceci est utile pour la mise à jour dynamique via AJAX
    $total_cart_items = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_cart_items += $item['quantity'];
        }
    }

    // Si la requête était une requête AJAX
    if ($is_ajax) {
        // Envoie une réponse JSON avec le nouveau compte du panier
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'cart_count' => $total_cart_items]);
        exit; // Arrête l'exécution du script pour éviter toute sortie supplémentaire
    } else {
        // Si la requête n'était PAS AJAX (ex: soumission de formulaire normale, JS désactivé), redirige vers la page du panier
        header('Location: cart.php');
        exit; // Arrête l'exécution du script après la redirection
    }
} else {
    // Si la requête n'est pas POST ou si l'ID du produit n'est pas défini
    if ($is_ajax) {
        // Envoie une réponse JSON d'échec pour les requêtes AJAX invalides
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Requête invalide']);
        exit;
    } else {
        // Redirige vers la page d'accueil ou une page d'erreur pour les requêtes non-AJAX invalides
        header('Location: index.php');
        exit;
    }
}
