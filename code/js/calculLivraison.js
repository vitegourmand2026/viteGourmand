
const ADRESSE_RESTAURANT = "25 rue Turenne, 33000 Bordeaux"; 
const PRIX_BASE_BORDEAUX = 5.00;
const PRIX_PAR_KM = 0.59;
const CODE_POSTAL_BORDEAUX = "33000";

// Délai pour respecter limite API FOURNI
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// Fonction pour géocoder une adresse FOURNI
async function geocoderAdresse(adresse) {
    const response = await fetch(
        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(adresse)}&format=json&countrycodes=fr&limit=1`,
        {
            headers: {
                'User-Agent': 'RestaurantDelivery/1.0'
            }
        }
    );
    
    if (!response.ok) {
        throw new Error('Erreur lors du géocodage');
    }
    
    const data = await response.json();
    
    if (data.length === 0) {
        throw new Error('Adresse introuvable');
    }
    
    return [data[0].lon, data[0].lat];
}

// Fonction pour calculer la distance avec OSRM FOURNI
async function calculerDistance(coordsDepart, coordsArrivee) {
    const response = await fetch(
        `https://router.project-osrm.org/route/v1/driving/${coordsDepart[0]},${coordsDepart[1]};${coordsArrivee[0]},${coordsArrivee[1]}?overview=false`
    );
    
    if (!response.ok) {
        throw new Error('Erreur lors du calcul de distance');
    }
    
    const data = await response.json();
    
    if (data.code !== 'Ok') {
        throw new Error('Impossible de calculer l\'itinéraire');
    }
    
    return data.routes[0].distance / 1000;
}

// CALCUL PRIX LIVRAISON

function calculerPrixLivraison(distanceKm, codePostal) {
    
    if (codePostal === CODE_POSTAL_BORDEAUX) {
        return PRIX_BASE_BORDEAUX;
    }
    
    
    return PRIX_BASE_BORDEAUX + (distanceKm * PRIX_PAR_KM);
}

// FONCTION MAJ PRIX

function mettreAJourPrix(fraisLivraison) {
    const sousTotal = parseFloat(
        document.getElementById('sous_total')
            .textContent
            .replace('€', '')
            .replace(',', '.')
            .trim()
    );
    
    // CALCUL TOTAL FINAL

    const total = sousTotal + fraisLivraison;
    
    // MAJ AFFICHAGE

    const livraisonElement = document.getElementById('frais_livraison');
    if (livraisonElement) {
        livraisonElement.textContent = fraisLivraison.toFixed(2) + ' €';
    }
    
    // MAJ TOTAL

    const totalElement = document.getElementById('total');
    if (totalElement) {
        const strongElement = totalElement.querySelector('strong');
        if (strongElement) {
            strongElement.textContent = total.toFixed(2) + ' €';
        } else {
            totalElement.innerHTML = '<strong>' + total.toFixed(2) + ' €</strong>';
        }
    }
    
    // MAJ HIDDEN

    const champLivraison = document.querySelector('input[name="frais_livraison"]');
    const champTotal = document.querySelector('input[name="total"]');
    
    if (champLivraison) {
        champLivraison.value = fraisLivraison.toFixed(2);
    }
    
    if (champTotal) {
        champTotal.value = total.toFixed(2);
    }
}

// FONCTION CHARGEMENT

function afficherChargement(afficher) {
    const livraisonElement = document.getElementById('frais_livraison');
    
    if (livraisonElement && afficher) {
        livraisonElement.innerHTML = '<span style="color: #666;">Calcul...</span>';
    }
}



// FONCTION CALCUL

async function calculerFraisLivraison() {
    const adresseInput = document.getElementById('adresse');
    const codePostalInput = document.getElementById('code_postal');
    const villeInput = document.getElementById('ville');
    
    const adresse = adresseInput.value.trim();
    const codePostal = codePostalInput.value.trim();
    const ville = villeInput.value.trim();
    
// VRIF FIELDS

    if (!adresse || !codePostal || !ville) {
        return;
    }
    
    const adresseComplete = `${adresse}, ${codePostal} ${ville}`;
    
    try {
        afficherChargement(true);
        
        
        const coordsRestaurant = await geocoderAdresse(ADRESSE_RESTAURANT);
        await delay(1100); 
        
        
        const coordsClient = await geocoderAdresse(adresseComplete);
        await delay(200);
        
        
        const distance = await calculerDistance(coordsRestaurant, coordsClient);
        
        
        const prixLivraison = calculerPrixLivraison(distance, codePostal);
        
        
        mettreAJourPrix(prixLivraison);
        
    } catch (error) {
        afficherErreur(error.message);
        
        mettreAJourPrix(PRIX_BASE_BORDEAUX);
    }
}

// Initialisation au chargement de la page FOURNI 
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les écouteurs d'événements FOURNI
    const adresseInput = document.getElementById('adresse');
    const codePostalInput = document.getElementById('code_postal');
    const villeInput = document.getElementById('ville');
    
    if (adresseInput && codePostalInput && villeInput) {
        // Calcul quand l'utilisateur quitte le champ FOURNI
        adresseInput.addEventListener('blur', calculerFraisLivraison);
        codePostalInput.addEventListener('blur', calculerFraisLivraison);
        villeInput.addEventListener('blur', calculerFraisLivraison);
    }
});