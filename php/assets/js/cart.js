
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmationModal');
    const closeButton = document.querySelector('.close-button');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const cancelDeleteButton = document.getElementById('cancelDeleteButton');
    const deleteForms = document.querySelectorAll('.delete-item-form'); // Sélectionne tous les formulaires de suppression
    const modalItemDetails = modal.querySelector('.modal-item-details');

    let currentItemId = null; // Pour stocker l'ID du produit à supprimer

    // Fonction pour ouvrir la modale
    function openModal(itemId, itemName, itemPrice, itemImage) {
        currentItemId = itemId;
        modalItemDetails.innerHTML = `
                <img src="assets/images/${itemImage}" alt="${itemName}">
                <div class="modal-item-info">
                    <strong>${itemName}</strong>
                    <span>${parseFloat(itemPrice).toFixed(2)} €</span>
                </div>
            `;
        modal.style.display = 'flex'; // Change display à 'flex' pour centrer via CSS
        document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }

    // Fonction pour fermer la modale
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Réactive le défilement de la page
        currentItemId = null; // Réinitialise l'ID
    }

    // Attacher les écouteurs d'événements aux boutons "Supprimer" de chaque article
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche la soumission normale du formulaire

            // Récupérer les données du produit à partir des champs cachés ou d'attributs de données
            const itemId = form.querySelector('input[name="id"]').value;

            // Récupération des infos depuis la ligne du tableau
            const row = form.closest('tr');
            const itemName = row.querySelector('td:nth-child(2)').textContent; // Nom (2ème td)
            const itemPrice = row.querySelector('td:nth-child(3)').textContent.replace(' €', '').trim(); // Prix (3ème td)
            const itemImage = row.querySelector('td:nth-child(1) img').getAttribute('src').split('/').pop(); // Nom de l'image (1er td img)

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
            tempForm.action = 'remove-cart.php';
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
