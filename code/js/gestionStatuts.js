document.addEventListener('DOMContentLoaded', function() {
    const statusBadges = document.querySelectorAll('.status-badge');
    const rightCard = document.querySelector('.right-card');
    const overlay = document.getElementById('overlay');
    const statusItems = document.querySelectorAll('.status-item');
    const motifContainer = document.getElementById('motif-container');
    const motifTextarea = document.getElementById('motif-annulation');
    const btnEnvoyer = document.getElementById('btn-envoyer-annulation');
    
    let currentCommandeId = null;
    let selectedStatus = null;

    // Ouvrir le menu des statuts
    statusBadges.forEach(badge => {
        badge.addEventListener('click', function(e) {
            e.stopPropagation();
            const commandeItem = this.closest('.user-item');
            currentCommandeId = commandeItem.getAttribute('data-commande-id');
            
            rightCard.classList.add('open');
            overlay.classList.add('visible');
            motifTextarea.value = '';
            selectedStatus = null;
        });
    });

    // Fermer les menus en cliquant sur l'overlay
    overlay.addEventListener('click', function() {
        rightCard.classList.remove('open');
        overlay.classList.remove('visible');
        motifContainer.classList.remove('show');
        currentCommandeId = null;
        selectedStatus = null;
    });

    // Gérer le clic sur les statuts
    statusItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const newStatus = this.getAttribute('data-status');
            
            // Si le statut est "annulée", ouvrir la zone motif
            if (newStatus === 'annulee') {
                selectedStatus = 'annulee';
                motifContainer.classList.add('show');
            } else {
                // Pour les autres statuts, rediriger directement
                window.location.href = '/code/process/admin_update_statut.php?id=' + currentCommandeId + '&statut=' + newStatus;
            }
        });
    });

    // Gérer le clic sur le bouton Envoyer
    btnEnvoyer.addEventListener('click', function() {
        const motif = motifTextarea.value.trim();
        
        if (!currentCommandeId) {
            alert('Erreur: aucune commande sélectionnée');
            return;
        }
        
        if (motif === '') {
            alert('Veuillez indiquer un motif d\'annulation');
            return;
        }
        
        // Rediriger avec le motif
        const motifEncoded = encodeURIComponent(motif);
        window.location.href = '/code/process/admin_update_statut.php?id=' + currentCommandeId + '&statut=annulee&motif_annulation=' + motifEncoded;
    });
});