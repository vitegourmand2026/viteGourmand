// Confirmation simple avant suppression
document.addEventListener('DOMContentLoaded', function() {
    const btnSupprimer = document.querySelector('.btn-supprimer');
    
    if (btnSupprimer) {
        btnSupprimer.addEventListener('click', function() {
            // Confirmation native du navigateur
            if (confirm('Êtes-vous sûr de vouloir supprimer ce menu ?\n\nCette action est irréversible !')) {
                // Soumettre le formulaire de suppression
                document.getElementById('deleteForm').submit();
            }
        });
    }
});