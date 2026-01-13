// open btn essayer de faire 2 const pour tout

const menuBtn = document.getElementById('menuBtn')
const optionMenu = document.getElementById('optionMenu')


// Open menu essayer de faire une seule fonction pour tout

menuBtn.addEventListener('click', () => {
   optionMenu.classList.toggle('open');
   overlay.classList.toggle('visible');
    
});

// Fermer le menu en cliquant sur l'overlay

optionMenu.classList.remove('open')
overlay.classList.remove('visible');