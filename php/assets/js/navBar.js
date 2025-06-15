const header = document.querySelector("header");
let lastScrollY = window.scrollY;
let scrollingUp = false;

window.addEventListener("scroll", () => {
    let currentScrollY = window.scrollY;

    if (currentScrollY < lastScrollY) {
        // remonte → Affiche le header
        scrollingUp = true;
    } else {
        // descend → Cache le header
        scrollingUp = false;
    }

    if (scrollingUp) {
        header.style.transform = "translateY(0)";
        header.style.opacity = "1";
    } else {
        header.style.transform = "translateY(-100%)";
        header.style.opacity = "0";
    }

    // Met à jour la position du scroll
    lastScrollY = currentScrollY;
});


const menuToggle = document.getElementById("burgerBtn");
const mobileMenu = document.querySelector(".mobile-menu");

menuToggle.addEventListener("click", () => {
    mobileMenu.classList.toggle("open");
});




