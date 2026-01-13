<?php

include '../../config.php';


// RECUP ID MENU AVEC URL

$menu_id = isset($_GET['id']) ? intval($_GET['id']) : 0;?>

<?php
session_start();
if ($_SESSION['role'] === 'admin') {
    include '../admin_header.php';
} elseif ($_SESSION['role'] === 'employee') {
    include '../../employee/employe_header.php';
}
?>

<?php

// REQUETE INFO MENU

$query = "SELECT theme, titre, regime, prix, personne, description FROM menus WHERE menu_id = :menu_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt->execute();
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header('Location: /code/php/menus.php');
    exit;
}

// REQUETE ALLERGENES MENU

$query_allergenes = "SELECT a.nom
                     FROM menu_allergenes ma
                     INNER JOIN allergenes a ON ma.allergenes_id = a.id
                     WHERE ma.menu_id = :menu_id
                     ORDER BY a.nom";

$stmt_allergenes = $pdo->prepare($query_allergenes);
$stmt_allergenes->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt_allergenes->execute();
$allergenes = $stmt_allergenes->fetchAll(PDO::FETCH_COLUMN);



$query_plates = "SELECT p.plate_id, p.name, p.image, c.name AS categories
                 FROM plates p
                 INNER JOIN categories c ON p.categorie_id = c.id
                 WHERE p.menu_id = :menu_id
                 ORDER BY c.ordre";

$stmt_plates = $pdo->prepare($query_plates);
$stmt_plates->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt_plates->execute();
$plates = $stmt_plates->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
        <link rel="stylesheet" href="/code/css/detailMenuAdmin.css?v=<?php echo time(); ?>">
        <title>Detail menu administrateur</title>
    </head>

    <body>  
    
        

    <div id="contentView">

        <div class= secondMenu>
            <p>Régime : <?php echo htmlspecialchars($menu['regime']); ?></p>
            <p><?php echo number_format($menu['prix'], 0); ?>€ (<?php echo $menu['personne']; ?> pers)</p>
            <?php if (!empty($allergenes)): ?>
                <button id="allergieBtn">Liste des allergènes</button>
            <?php endif; ?>
        </div>

<!--ALLERGENES-->

        <?php if (!empty($allergenes)): ?>

            <div class="allergie-menu" id="allergieMenu">
                <div class="allergie-header">
                    <h3>Allergènes</h3>
                </div>
                <div class="allergenes">
                    <ol>
                        <?php foreach ($allergenes as $allergene): ?>
                            <li><?php echo htmlspecialchars($allergene); ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        <?php endif; ?>

<!--TITRES-->       

        <div class=theme>
            <p class="titre"><?php echo htmlspecialchars($menu['theme']); ?></p>
        </div>
        
        
        <h4>"<?php echo htmlspecialchars($menu['titre']); ?>"</h4>
        <p><?php echo htmlspecialchars($menu['description']); ?></p>

<!--PLATS-->

        <div id="cardsContainer">
            <?php foreach ($plates as $plate): ?>
                <div class= "card">
                    <div class= "card-header">
                        <?= nl2br(htmlspecialchars($plate['name'])) ?>
                    </div>

                    <div class="image-container">
                        <img src="/<?= htmlspecialchars($plate['image']) ?>" 
                            alt="<?= htmlspecialchars($plate['name']) ?>">
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div> 
    
<!-- FORMULAIRE MODIFICATION -->

    <div class="edit-form-container" id="editForm">
        <div class="edit-form-content">
            <h2>Modifier le menu</h2>
            
            <form method="POST" action="/code/process/update_Menu.php">
                <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
                
                <div class="form-group">
                    <label for="theme">Thème</label>
                    <input type="text" id="theme" name="theme" value="<?php echo htmlspecialchars($menu['theme']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($menu['titre']); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="regime">Régime</label>
                        <select id="regime" name="regime" required>
                            <option value="Classique" <?php echo $menu['regime'] == 'Classique' ? 'selected' : ''; ?>>Classique</option>
                            <option value="Vegan" <?php echo $menu['regime'] == 'Vegan' ? 'selected' : ''; ?>>Vegan</option>
                            <option value="Sans porc" <?php echo $menu['regime'] == 'Sans porc' ? 'selected' : ''; ?>>Sans porc</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="prix">Prix (€)</label>
                        <input type="number" id="prix" name="prix" value="<?php echo $menu['prix']; ?>" min="0" step="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="personne">Personnes</label>
                        <input type="number" id="personne" name="personne" value="<?php echo $menu['personne']; ?>" min="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($menu['description']); ?></textarea>
                </div>

<!-- IMG PLATS -->

<div class="form-group">
    <label>Images des plats</label>
    <div class="plates-images-grid">
        <?php foreach ($plates as $plate): ?>
            <div class="plate-image-item">
                <div class="current-plate-image">
                    <img src="/<?php echo htmlspecialchars($plate['image']); ?>" alt="<?php echo htmlspecialchars($plate['name']); ?>">
                    <p><?php echo htmlspecialchars($plate['name']); ?></p>
                </div>
                <input type="file" name="plate_images[<?php echo $plate['plate_id']; ?>]" accept="image/*">
                
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ALLERGENES -->   

<div class="form-group">
    <label>Allergènes</label>
        <div class="allergenes-checkboxes">
    
            <?php

// SELECT ALLERGENES

$query_all_allergenes = "SELECT id, nom FROM allergenes ORDER BY nom";
$stmt_all = $pdo->query($query_all_allergenes);
$all_allergenes = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
                        
// SELECT ALLERGENES ID

$query_menu_allergenes = "SELECT allergenes_id FROM menu_allergenes WHERE menu_id = :menu_id";
$stmt_menu_all = $pdo->prepare($query_menu_allergenes);
$stmt_menu_all->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt_menu_all->execute();
$menu_allergenes_ids = $stmt_menu_all->fetchAll(PDO::FETCH_COLUMN);
                        
            foreach ($all_allergenes as $allergene):
            $checked = in_array($allergene['id'], $menu_allergenes_ids) ? 'checked' : '';
            ?>

            <label class="checkbox-label">
            <input type="checkbox" name="allergenes[]" value="<?php echo $allergene['id']; ?>" <?php echo $checked; ?>>
            <?php echo htmlspecialchars($allergene['nom']); ?>
            </label>
        <?php endforeach; ?>
    </div>
</div>
                
                <div class="form-buttons">
                    <button type="button" id="cancelEdit" class="btn-cancel-edit">Annuler</button>
                    <button type="submit" class="btn-save">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    

<!-- FOOTER -->

        <footer>
          
            <div class="footer-btn">
                <button class="btn-modifier">MODIFIER</button>
                <button class="btn-supprimer">SUPPRIMER</button>
            </div>
            </footer>

<!-- OVERLAY -->

            <div class="overlay" id="overlay"></div>
                
<!-- HIDDEN FORMULAIRE -->

        <form id="deleteForm" method="POST" action="/code/process/delete_Menu.php" style="display: none;">
            <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
        </form>
        <script src="/code/js/allergie.js?v=<?php echo time(); ?>"></script>
        <script src="/code/js/confirmDelete.js?v=<?php echo time(); ?>"></script>
        <script src="/code/js/editMenu.js?v=<?php echo time(); ?>"></script>
    </body>
</html>