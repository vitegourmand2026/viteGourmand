
const allergieBtn = document.getElementById('allergieBtn');
const allergieMenu = document.getElementById('allergieMenu');



// Ouvrir le menu
allergieBtn.addEventListener('click', () => {
    allergieMenu.classList.add('open');
    overlay.classList.add('visible');
});


// Fermer en cliquant sur l'overlay
overlay.addEventListener('click', () => {
    allergieMenu.classList.remove('open');
    overlay.classList.remove('visible');
});
