/**
 * Bascule l'affichage du menu de quantité et la rotation de la flèche pour une carte spécifique.
 * @param {HTMLElement} displayElement L'élément .quantity-display qui a été cliqué.
 */
function toggleMenu(displayElement) {

    const selector = displayElement.closest('.quantity-selector');
    if (!selector) return; // Ajoute une vérification de nullité pour 'selector'

    const menu = selector.querySelector('.quantity-list');
    const arrow = displayElement.querySelector('.arrow'); 
    const isOpen = menu && menu.style.display === 'grid'; // Vérifie si 'menu' existe avant d'accéder à style

    // Ferme tous les autres menus ouverts
    document.querySelectorAll('.quantity-list[style*="display: grid"]').forEach(openMenu => {
        if (openMenu !== menu) { // Ne pas fermer le menu actuel
            openMenu.style.display = 'none';
            // Ajoute des vérifications de nullité pour otherArrow
            const otherArrow = openMenu.closest('.quantity-selector') ? openMenu.closest('.quantity-selector').querySelector('.arrow') : null;
            if (otherArrow) {
                otherArrow.classList.remove('rotate-up');
            }
        }
    });

    // Ajoute des vérifications de nullité pour 'menu' et 'arrow' avant de les utiliser
    if (menu && arrow) {
        if (isOpen) {
            menu.style.display = 'none';
            arrow.classList.remove('rotate-up');
        } else {
            // Si le menu est fermé, l'ouvrir et faire pivoter la flèche vers le haut
            menu.style.display = 'grid';
            arrow.classList.add('rotate-up');
        }
    }
}

/**
 * Met à jour la quantité sélectionnée et le champ caché du formulaire.
 * @param {number|string} value La nouvelle quantité sélectionnée ou la valeur de l'input "Autre".
 * @param {HTMLElement} clickedElement L'élément (bouton ou input) qui a été cliqué.
 */
function selectQuantity(value, clickedElement) {
    const selector = clickedElement.closest('.quantity-selector');
    if (!selector) return; // Ajoute une vérification de nullité pour 'selector'

    const selectedQuantitySpan = selector.querySelector('.selected-quantity');
    const hiddenQuantityInput = selector.querySelector('.hidden-quantity-input');
    const menu = selector.querySelector('.quantity-list');
    const arrow = selector.querySelector('.arrow');
    const otherQuantityInput = selector.querySelector('.quantity-input-container input[type="number"]');

    let finalQuantity;

    if (clickedElement.type === 'number') {
        // Si la valeur vient de l'input "Autre"
        let numValue = parseInt(value);
        if (isNaN(numValue) || numValue < 1) {
            finalQuantity = 1; // Défaut à 1 si non valide
            if (otherQuantityInput) otherQuantityInput.value = 1;
        } else {
            finalQuantity = numValue;
        }
    } else {
        // Si la valeur vient d'un bouton prédéfini
        finalQuantity = value;
        if (otherQuantityInput) otherQuantityInput.value = ''; // Vide l'input "Autre"
    }

    // Ajoute des vérifications de nullité avant d'accéder aux propriétés
    if (selectedQuantitySpan) selectedQuantitySpan.innerText = finalQuantity;
    if (hiddenQuantityInput) hiddenQuantityInput.value = finalQuantity;

    // Ferme le menu après sélection
    if (menu) menu.style.display = 'none';
    // Fait pivoter la flèche vers le bas
    if (arrow) arrow.classList.remove('rotate-up');
}

/**
 * Gère les clics en dehors de n'importe quel sélecteur de quantité pour fermer les menus ouverts.
 * @param {Event} event L'objet événement du clic.
 */
document.addEventListener('click', function (event) {
    // Sélectionne tous les menus de quantité actuellement affichés
    document.querySelectorAll('.quantity-list[style*="display: grid"]').forEach(menu => {
        const quantitySelector = menu.closest('.quantity-selector');
        // Ajoute une vérification de nullité pour 'arrow'
        const arrow = quantitySelector ? quantitySelector.querySelector('.arrow') : null;

        // Si le clic n'est pas à l'intérieur de ce sélecteur de quantité
        // Ajoute une vérification de nullité pour 'quantitySelector'
        if (quantitySelector && !quantitySelector.contains(event.target)) {
            menu.style.display = 'none';
            if (arrow) {
                arrow.classList.remove('rotate-up');
            }
        }
    });
});
