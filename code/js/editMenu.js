document.addEventListener('DOMContentLoaded', function() {
    const btnModifier = document.querySelector('.btn-modifier');
    const editForm = document.getElementById('editForm');
    const cancelEdit = document.getElementById('cancelEdit');
    const overlay = document.getElementById('overlay');
    const contentView = document.getElementById('contentView');
    
    // Afficher le formulaire de modification
    if (btnModifier) {
        btnModifier.addEventListener('click', function() {
            contentView.style.display = 'none';
            editForm.classList.add('visible');
            overlay.classList.add('visible');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Annuler la modification
    if (cancelEdit) {
        cancelEdit.addEventListener('click', function() {
            contentView.style.display = 'block';
            editForm.classList.remove('visible');
            overlay.classList.remove('visible');
            document.body.style.overflow = 'auto';
        });
    }
    
    // Fermer au clic sur l'overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            if (editForm.classList.contains('visible')) {
                contentView.style.display = 'block';
                editForm.classList.remove('visible');
                overlay.classList.remove('visible');
                document.body.style.overflow = 'auto';
            }
        });
    }
});