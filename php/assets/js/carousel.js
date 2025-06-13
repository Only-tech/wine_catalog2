let carouselTrack;
let prevButton;
let nextButton;
let numClones;
let totalCards;
let clonedData;
// let currentIndex;
let carouselCards;
let cardWidth;
let autoScrollInterval;

// Fonction pour créer un élément de carte de vin
const createWineCard = (wine) => {
    const card = document.createElement('div');
    card.classList.add('in-carousel');

    card.innerHTML = `
        <div class="wine-card in-carousel">
            <img src="${wine.image}" alt="${wine.name}">
            <div class="wine-info">
                <h3>${wine.name}</h3>
                <p>${wine.price} €</p>
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
                    <a href="product.php?id=<?php echo $wine['id']; ?>" class="button-view-product">Voir le produit</a>
                </div>
                <p class="review-product">${wine.avis}</p>
            </div>
        </div>
    `;
    return card;
};

// Fonction pour calculer la valeur de transform
const getTransformValue = (index) => {
    // Calcul de la largeur totale de la carte (largeur + marges)
    if (!carouselCards || carouselCards.length === 0) {
        return 'translateX(0px)'; // Fallback
    }
    const cardStyle = getComputedStyle(carouselCards[0]);
    const cardMarginLeft = parseFloat(cardStyle.marginLeft);
    const cardMarginRight = parseFloat(cardStyle.marginRight);
    // cardWidth est déjà calculé avec les marges dans DOMContentLoaded
    
    const offset = -index * cardWidth;
    const containerWidth = carouselTrack.parentElement.offsetWidth;
    // Calcule l'offset pour centrer la carte active dans le conteneur du carrousel
    const centerOffset = (containerWidth / 2) - (cardWidth / 2);
    return `translateX(${offset + centerOffset}px)`;
};


// Fonction pour mettre à jour la position du carrousel et l'état actif
const updateCarousel = (smoothTransition = true) => {
    carouselTrack.style.transition = smoothTransition ? 'transform 0.5s ease-in-out' : 'none';
    carouselTrack.style.transform = getTransformValue(currentIndex);

    carouselCards.forEach((card, index) => {
        card.classList.remove('active');
        if (index === currentIndex) {
            card.classList.add('active');
        }
    });
};

const startAutoScroll = () => {
    stopAutoScroll(); // Efface tout intervalle existant
    autoScrollInterval = setInterval(() => {
        // Simule un clic sur le bouton "next"
        // Pour éviter l'erreur si nextButton n'est pas encore défini
        if (nextButton) {
            nextButton.click();
        }
    }, 3000);
};

const stopAutoScroll = () => {
    clearInterval(autoScrollInterval);
};

document.addEventListener('DOMContentLoaded', () => {
    // Vérifie si wineCardsData est défini et non vide
    // Cette variable est censée être injectée par PHP dans index.php
    if (typeof wineCardsData === 'undefined' || !Array.isArray(wineCardsData) || wineCardsData.length === 0) {
        console.warn("wineCardsData n'est pas défini ou est vide. Le carrousel ne sera pas initialisé.");
        return;
    }

    carouselTrack = document.querySelector('.carousel-wine-track'); // ID est 'carouselTrack'
    prevButton = document.querySelector('.carousel-button.prev');
    nextButton = document.querySelector('.carousel-button.next');

    // Duplique les cartes pour un effet de boucle infinie sans accroc
    numClones = 3; 
    totalCards = wineCardsData.length;
    clonedData = [
        ...wineCardsData.slice(-numClones),
        ...wineCardsData,
        ...wineCardsData.slice(0, numClones) 
    ];

    currentIndex = numClones; // Commence à la première "vraie" carte

    // Remplie la piste du carrousel
    clonedData.forEach(wine => {
        carouselTrack.appendChild(createWineCard(wine));
    });

    carouselCards = document.querySelectorAll('.in-carousel');
    // Calcule la largeur d'une carte, y compris ses marges
    cardWidth = carouselCards.length > 0 ? carouselCards[0].offsetWidth + (parseFloat(getComputedStyle(carouselCards[0]).marginRight) || 0) * 2 : 0; // Utiliser || 0 pour les marges

    // Gère la boucle infinie après la transition
    carouselTrack.addEventListener('transitionend', () => {
        // Si nous sommes passés à une carte clonée à la fin (par un défilement fluide)
        if (currentIndex >= totalCards + numClones) {
            currentIndex = numClones; // Revient au vrai début
            updateCarousel(false); // Effectue le saut instantané
        }
        // Si nous sommes passés à une carte clonée au début (par un défilement fluide)
        else if (currentIndex < numClones) {
            currentIndex = totalCards + (currentIndex % totalCards); // Revenir à la vraie fin (ou équivalent)
            updateCarousel(false); // Effectue le saut instantané
        }
    });

    // Initialisation du carrousel
    updateCarousel();
    startAutoScroll();

    // Écouteurs d'événements pour les boutons de navigation
    prevButton.addEventListener('click', () => {
        stopAutoScroll(); 
        currentIndex--;
        updateCarousel();
        startAutoScroll();
    });

    nextButton.addEventListener('click', () => {
        stopAutoScroll(); 
        currentIndex++;
        updateCarousel();
        startAutoScroll();
    });

    // Met en pause le défilement automatique au survol, reprendre en sortant du survol
    const carouselContainer = document.querySelector('.carousel-wines');
    if (carouselContainer) {
        carouselContainer.addEventListener('mouseover', stopAutoScroll);
        carouselContainer.addEventListener('mouseleave', startAutoScroll);
    }


    // Ajuste le carrousel au redimensionnement de la fenêtre
    window.addEventListener('resize', () => {
        if (carouselCards.length > 0) {
            // Recalcule la largeur de la carte et mettre à jour la position pour qu'elle reste centrée
            cardWidth = carouselCards[0].offsetWidth + (parseFloat(getComputedStyle(carouselCards[0]).marginRight) || 0) * 2;
            carouselTrack.style.transition = 'none'; 
            carouselTrack.style.transform = getTransformValue(currentIndex);
            // Réactive la transition après un court délai pour permettre le rendu
            setTimeout(() => {
                carouselTrack.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }
    });
});

