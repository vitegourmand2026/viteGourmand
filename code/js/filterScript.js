// Récupérer les éléments
const filtresBtn = document.getElementById('filterButton');
const filtresMenu = document.getElementById('filtres-menu');
const validerBtn = document.getElementById('valider-btn');
const allCards = document.querySelectorAll('.card');
const overlayFiltres = document.getElementById('overlay');

// Ouvrir le menu des filtres
filtresBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    overlayFiltres.classList.add('visible');
    filtresMenu.classList.add('open');
});

// Fermer le menu des filtres quand on clique sur l'overlay
overlayFiltres.addEventListener('click', () => {
    if (filtresMenu.classList.contains('open')) {
        filtresMenu.classList.remove('open');
    }
});

// Sélection des filtres
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => btn.classList.toggle('open'));
});

// Appliquer les filtres au clic sur Valider
validerBtn.addEventListener('click', () => {
    const selected = {
        theme: [],
        regime: [],
        personne: [],
        prix: []
    };

    // Récupérer tous les filtres sélectionnés
    document.querySelectorAll('.filter-btn.open').forEach(btn => {
        const type = btn.getAttribute('data-filter');
        const value = btn.getAttribute('data-value');
        selected[type].push(value);
    });

    // Filtrer les cartes
    allCards.forEach(card => {
        const theme = card.getAttribute('data-theme');
        const prix = parseInt(card.getAttribute('data-prix'));
        const personne = card.getAttribute('data-personne');
        const regime = card.getAttribute('data-regime');
        
        let show = true;

        // Vérifier chaque type de filtre
        if (selected.theme.length > 0 && !selected.theme.includes(theme)) {
            show = false;
        }
        
        if (selected.regime.length > 0 && !selected.regime.includes(regime)) {
            show = false;
        }
        
        if (selected.personne.length > 0 && !selected.personne.includes(personne)) {
            show = false;
        }

        // Vérifier les plages de prix
        if (selected.prix.length > 0) {
            let matchPrice = false;
            selected.prix.forEach(range => {
                const [min, max] = range.split('-').map(Number);
                if (prix >= min && prix <= max) {
                    matchPrice = true;
                }
            });
            if (!matchPrice) {
                show = false;
            }
        }

        card.style.display = show ? 'block' : 'none';
    });

    // Fermer le menu
    overlayFiltres.classList.remove('visible');
    filtresMenu.classList.remove('open');
});