
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmationModal');
    const closeButton = document.querySelector('.close-button');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const cancelDeleteButton = document.getElementById('cancelDeleteButton');
    // Sélectionne tous les formulaires de suppression
    const deleteForms = document.querySelectorAll('.delete-item-form');
    const modalItemDetails = modal.querySelector('.modal-item-details');

    let currentItemId = null;
    let currentItemName = null;
    let currentItemPrice = null;
    let currentItemImage = null;

    // Fonction pour ouvrir la modale
    function openModal(itemId, itemName, itemPrice, itemImage) {
        currentItemId = itemId;
        currentItemName = itemName;
        currentItemPrice = itemPrice;
        currentItemImage = itemImage;

        modalItemDetails.innerHTML = `
            <img src="assets/images/${itemImage}" alt="${itemName}">
            <div class="modal-item-info">
                <strong>${itemName}</strong>
                <span>${parseFloat(itemPrice).toFixed(2)} €</span>
            </div>
        `;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }

    // Fonction pour fermer la modale
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Réactive le défilement de la page
        currentItemId = null;
        currentItemName = null;
        currentItemPrice = null;
        currentItemImage = null;
    }

    // Attache les écouteurs d'événements aux boutons "Supprimer" de chaque produit
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche la soumission normale du formulaire

            // Récupére les données du produit à partir des champs cachés du formulaire
            const itemId = this.querySelector('input[name="id"]').value;
            const itemName = this.querySelector('input[name="item_name"]').value;
            const itemPrice = this.querySelector('input[name="item_price"]').value;
            const itemImage = this.querySelector('input[name="item_image"]').value;

            openModal(itemId, itemName, itemPrice, itemImage);
        });
    });

    // Écouteurs pour les boutons de la modale
    closeButton.addEventListener('click', closeModal);
    cancelDeleteButton.addEventListener('click', closeModal);

    // Gère le clic en dehors de la modale pour la fermer
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });

    // Gère le bouton de confirmation de suppression
    confirmDeleteButton.addEventListener('click', function() {
        if (currentItemId) {
            // Crée un formulaire temporaire et le soumet pour supprimer le produit
            const tempForm = document.createElement('form');
            tempForm.action = 'delete-product.php'; // CIBLE MAINTENANT delete-product.php
            tempForm.method = 'post';
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'id';
            hiddenInput.value = currentItemId;
            tempForm.appendChild(hiddenInput);
            document.body.appendChild(tempForm); // Ajout au DOM pour la soumission
            tempForm.submit();
        }
    });
});
