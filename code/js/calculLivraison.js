// Configuration
const ADRESSE_RESTAURANT = "25 rue Turenne, 33000 Bordeaux"; // ← Changez avec votre adresse
const PRIX_BASE_BORDEAUX = 5.00;
const PRIX_PAR_KM = 0.59;
const CODE_POSTAL_BORDEAUX = "33000";

// Délai pour respecter limite API
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// Fonction pour géocoder une adresse
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

// Fonction pour calculer la distance avec OSRM
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

// Fonction pour calculer le prix de livraison
function calculerPrixLivraison(distanceKm, codePostal) {
    // Si c'est Bordeaux (33000), prix fixe de 5€
    if (codePostal === CODE_POSTAL_BORDEAUX) {
        return PRIX_BASE_BORDEAUX;
    }
    
    // Sinon : 5€ + 0.59€/km
    return PRIX_BASE_BORDEAUX + (distanceKm * PRIX_PAR_KM);
}

// Fonction pour mettre à jour l'affichage des prix
function mettreAJourPrix(fraisLivraison) {
    // Récupérer le sous-total
    const sousTotal = parseFloat(
        document.getElementById('sous_total')
            .textContent
            .replace('€', '')
            .replace(',', '.')
            .trim()
    );
    
    // Calculer le nouveau total
    const total = sousTotal + fraisLivraison;
    
    // Mettre à jour l'affichage de la livraison
    const livraisonElement = document.getElementById('frais_livraison');
    if (livraisonElement) {
        livraisonElement.textContent = fraisLivraison.toFixed(2) + ' €';
    }
    
    // Mettre à jour l'affichage du total
    const totalElement = document.getElementById('total');
    if (totalElement) {
        const strongElement = totalElement.querySelector('strong');
        if (strongElement) {
            strongElement.textContent = total.toFixed(2) + ' €';
        } else {
            totalElement.innerHTML = '<strong>' + total.toFixed(2) + ' €</strong>';
        }
    }
    
    // Mettre à jour les champs cachés
    const champLivraison = document.querySelector('input[name="frais_livraison"]');
    const champTotal = document.querySelector('input[name="total"]');
    
    if (champLivraison) {
        champLivraison.value = fraisLivraison.toFixed(2);
    }
    
    if (champTotal) {
        champTotal.value = total.toFixed(2);
    }
}

// Fonction pour afficher un message de chargement
function afficherChargement(afficher) {
    const livraisonElement = document.getElementById('frais_livraison');
    
    if (livraisonElement && afficher) {
        livraisonElement.innerHTML = '<span style="color: #666;">⏳ Calcul...</span>';
    }
}

// Fonction pour afficher une erreur
function afficherErreur(message) {
    const livraisonElement = document.getElementById('frais_livraison');
    
    if (livraisonElement) {
        livraisonElement.innerHTML = `<span style="color: #e74c3c;" title="${message}">⚠️ 5.00 €</span>`;
    }
}

// Fonction principale de calcul
async function calculerFraisLivraison() {
    const adresseInput = document.getElementById('adresse');
    const codePostalInput = document.getElementById('code_postal');
    const villeInput = document.getElementById('ville');
    
    const adresse = adresseInput.value.trim();
    const codePostal = codePostalInput.value.trim();
    const ville = villeInput.value.trim();
    
    // Vérifier que tous les champs sont remplis
    if (!adresse || !codePostal || !ville) {
        return;
    }
    
    // Construire l'adresse complète
    const adresseComplete = `${adresse}, ${codePostal} ${ville}`;
    
    try {
        afficherChargement(true);
        
        // 1. Géocoder l'adresse du restaurant
        const coordsRestaurant = await geocoderAdresse(ADRESSE_RESTAURANT);
        await delay(1100); // Respect limite 1 req/sec
        
        // 2. Géocoder l'adresse du client
        const coordsClient = await geocoderAdresse(adresseComplete);
        await delay(200);
        
        // 3. Calculer la distance
        const distance = await calculerDistance(coordsRestaurant, coordsClient);
        
        // 4. Calculer le prix
        const prixLivraison = calculerPrixLivraison(distance, codePostal);
        
        // 5. Mettre à jour l'affichage
        mettreAJourPrix(prixLivraison);
        
    } catch (error) {
        afficherErreur(error.message);
        // En cas d'erreur, garder le prix par défaut de 5€
        mettreAJourPrix(PRIX_BASE_BORDEAUX);
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les écouteurs d'événements
    const adresseInput = document.getElementById('adresse');
    const codePostalInput = document.getElementById('code_postal');
    const villeInput = document.getElementById('ville');
    
    if (adresseInput && codePostalInput && villeInput) {
        // Calcul quand l'utilisateur quitte le champ
        adresseInput.addEventListener('blur', calculerFraisLivraison);
        codePostalInput.addEventListener('blur', calculerFraisLivraison);
        villeInput.addEventListener('blur', calculerFraisLivraison);
    }
});