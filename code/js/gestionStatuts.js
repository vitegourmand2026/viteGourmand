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

// OPEN MENU STATUS

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

// OVERLAY CLOSE

    overlay.addEventListener('click', function() {
        rightCard.classList.remove('open');
        overlay.classList.remove('visible');
        motifContainer.classList.remove('show');
        currentCommandeId = null;
        selectedStatus = null;
    });

// GESTON CLIC

    statusItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const newStatus = this.getAttribute('data-status');
            
// CLIC ANNULÉE

            if (newStatus === 'annulee') {
                selectedStatus = 'annulee';
                motifContainer.classList.add('show');
            } else {
                
                window.location.href = '/code/process/admin_update_statut.php?id=' + currentCommandeId + '&statut=' + newStatus;
            }
        });
    });

// ENVOYER

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
        
// MOTIF ANNULATION

        const motifEncoded = encodeURIComponent(motif);
        window.location.href = '/code/process/admin_update_statut.php?id=' + currentCommandeId + '&statut=annulee&motif_annulation=' + motifEncoded;
    });
});