<?php

include '../../config.php';?>

<?php
session_start();
if ($_SESSION['role'] === 'admin') {
    include '../admin_header.php';
} elseif ($_SESSION['role'] === 'employee') {
    include '../../employee/employe_header.php';
}
?>
<?php

// SELECT MENUS

$query = "SELECT * FROM menus ORDER BY menu_id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Italiana&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/code/css/gestionMenus.css?v=<?php echo time(); ?>">
    <title>gestion des menus</title>
</head>
<body>
    
<!-- CARTES MENUS -->

    <div id="cardsContainer">
        <?php if (count($menus) > 0): ?>
            <?php foreach ($menus as $menu): ?>
                <div class="card" 
                     data-theme="<?php echo htmlspecialchars($menu['theme']); ?>"
                     data-prix="<?php echo htmlspecialchars($menu['prix']); ?>"
                     data-personne="<?php echo htmlspecialchars($menu['personne']); ?>"
                     data-regime="<?php echo htmlspecialchars($menu['regime']); ?>">
                    
                    <div class="card-header">
                        <span class="badge-category"><?php echo htmlspecialchars($menu['theme']); ?></span>
                        <h2 class="dish-title">"<?php echo htmlspecialchars($menu['titre']); ?>"</h2>
                        <div class="persons">
                            <span><?php echo htmlspecialchars($menu['personne']); ?> personne<?php echo $menu['personne'] > 1 ? 's' : ''; ?></span>
                        </div>
                        <div class="details-badge">
                            <a href="/code/php/admin/sections/detailMenuAdmin.php?id=<?php echo $menu['menu_id']; ?>"><i class="fa-solid fa-circle-plus"></i></a>
                        </div>
                    </div>
                    
                    <div class="image-container">
                        <img src="/<?php echo htmlspecialchars($menu['image']); ?>" 
                             alt="<?php echo htmlspecialchars($menu['titre']); ?>"
                             class="dish-image">
                             
                        <div class="price-badge"><?php echo intval($menu['prix']); ?>€</div>
                    </div>
                    
                    <div class="card-footer">
                        <span class="badge-diet">Régime : <?php echo htmlspecialchars($menu['regime']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
       
        <?php endif; ?>
    </div>
    
    
</body>
</html>