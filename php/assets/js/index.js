document.addEventListener('DOMContentLoaded', function() {
    //  Code pour le son au survol/
    const sound = new Audio("assets/sounds/sound-card-mouse-enter.m4a");
    const cards = document.querySelectorAll(".wine-card");

    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            // sound.currentTime = 0;
            sound.play();
        });
    });
    //  FIN du code pour le son au survol

    //  DEBUT du code pour l'ajout au panier AJAX et le modal 
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    const cartItemCountSpan = document.getElementById('cart-item-count');

    // Éléments du modal
    const modal = document.getElementById('addToCartModal');
    const modalMessage = modal ? modal.querySelector('.modal-message') : null;
    const modalItemDetails = modal.querySelector('.modal-item-details');
    const closeButton = modal ? modal.querySelector('.close-button') : null;
    const viewCartBtn = modal ? modal.querySelector('#viewCartBtn') : null;
    const continueShoppingBtn = modal ? modal.querySelector('#continueShoppingBtn') : null;

    // Fonction pour ouvrir le modal
    function openModal(message, itemId, itemName, itemPrice, itemImage) {
        currentItemId = itemId;
        modalMessage.textContent = message;
        modalItemDetails.innerHTML = `
                <img src="${itemId}" alt="${itemName}">
                <div class="modal-item-info">
                    <strong>${itemName}</strong>
                    <span>${parseFloat(itemPrice).toFixed(2)} €</span>
                </div>
            `;
        modal.style.display = 'flex'; // Change display à 'flex' pour centrer via CSS
        document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }

    // Fonction pour fermer le modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Réactive le défilement de la page
        currentItemId = null; // Réinitialise l'ID
    }

    // Écouteurs pour les boutons du modal
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }
    if (viewCartBtn) {
        viewCartBtn.addEventListener('click', function() {
            window.location.href = 'cart.php'; // Redirige vers la page du panier
        });
    }
    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', closeModal);
    }

    // Gère le clic en dehors du modal pour le fermer
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });


    addToCartForms.forEach(form => {
        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Empêche la soumission normale du formulaire

            // Extrait les détails du produit de closest .product-card
            const wineCard = form.closest('.wine-card');
            let productName = '';
            let productPrice = '';
            let productImageSrc = '';

            if (wineCard) {
                const itemName = wineCard.querySelector('.wine-info h3');
                const itemPrice = wineCard.querySelector('.wine-info p');
                const itemImage = wineCard.querySelector('.wine-card img');

                if (itemName) {
                    productName = itemName.textContent.trim();
                }
                if (itemPrice) {
                    productPrice = itemPrice.textContent.trim();
                }
                if (itemImage) {
                    productImageSrc = itemImage.src;
                }
            }
            

            const formData = new FormData(form);

            try {
                const response = await fetch('add-to-cart.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        if (cartItemCountSpan) {
                            cartItemCountSpan.textContent = data.cart_count;
                        }
                        // Ouvre le modal avec les détails du produit
                        openModal(
                            'Produit ajouté au panier ! Total: ' + data.cart_count + ' produit(s)',
                            productImageSrc,
                            productName,
                            productPrice
                        );
                    } else {
                        // Garde l'alert pour les erreurs
                        console.error('Erreur lors de l\'ajout au panier:', data.message);
                        alert('Erreur: ' + (data.message || 'Impossible d\'ajouter le produit au panier.'));
                    }
                } else {
                    console.error('Erreur réseau ou réponse non OK:', response.status, response.statusText);
                    alert('Une erreur réseau est survenue lors de l\'ajout au panier.');
                }
            } catch (error) {
                console.error('Erreur lors de la requête AJAX:', error);
                alert('Une erreur inattendue est survenue.'); 
            }
        });
    });
    // --- FIN du code pour l'ajout au panier AJAX ---
});