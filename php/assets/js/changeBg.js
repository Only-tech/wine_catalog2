// Liste des backgrounds disponibles
const backgrounds = [
    "url('assets/images/wine-catalog-bg-pinkWine.svg')",
    "url('assets/images/wine-catalog-bg-raisinBlack.svg')",
    "url('assets/images/wine-catalog-bg-blackTrace.svg')"
];

let currentIndex = localStorage.getItem('bgIndex') ? parseInt(localStorage.getItem('bgIndex')) : 0;
document.body.style.backgroundImage = backgrounds[currentIndex];

document.getElementById('changeBackground').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % backgrounds.length;
    document.body.style.backgroundImage = backgrounds[currentIndex];

    // Sauvegarde l'index du background
    localStorage.setItem('bgIndex', currentIndex);
});


const cardColors = ["rgba(247, 191, 214, 0.95)", "rgba(141, 140, 140, 0.95)", "rgba(255, 255, 255, 0.95)"]; // Associe chaque fond à une couleur

const productCards = document.querySelectorAll('.expandable-wine-card, .in-carousel, .admin-actions table, .cart-actions table, .modal-content, .no-results-message, .product-card');

// Applique la couleur des cartes selon l'index enregistré
if (localStorage.getItem('bgIndex')) {
    const savedIndex = parseInt(localStorage.getItem('bgIndex'));
    productCards.forEach(card => {
        card.style.backgroundColor = cardColors[savedIndex];
    });
}

// Change la couleur des cartes en même temps que le fond
document.getElementById('changeBackground').addEventListener('click', () => {
    productCards.forEach(card => {
        card.style.backgroundColor = cardColors[currentIndex];
    });
});

