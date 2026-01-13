// Feature: Gestion des menus avec filtres et catégories
<?php

include 'config.php';

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
    <link rel="stylesheet" href="/code/css/menus.css?v=<?php echo time(); ?>">
    <title>Menus</title>
</head>
<body>
    <?php include "header.php"; ?>
    
    <div class="container">
        <button class="filter-button" id="filterButton">Filtres</button>
    </div>
    
    
<!-- FILTER MENU -->

    <div class="filtres-menu" id="filtres-menu">
        <div class="filtres-section">
            <h3>Catégories</h3>
            <button class="filter-btn" data-filter="theme" data-value="Gastronomie">Gastronomie</button>
            <button class="filter-btn" data-filter="theme" data-value="Bistronomie">Bistronomie</button>
            <button class="filter-btn" data-filter="theme" data-value="Cuisine du monde">Cuisine du monde</button>
            <button class="filter-btn" data-filter="theme" data-value="Végétarien">Végétarien</button>
            <button class="filter-btn" data-filter="theme" data-value="Événements">Événements</button>
        </div>
        
        <div class="filtres-section">
            <h3>Prix</h3>
            <button class="filter-btn" data-filter="prix" data-value="20-25">20-25€</button>
            <button class="filter-btn" data-filter="prix" data-value="25-30">25-30€</button>
            
            
        </div>
        
        <div class="filtres-section">
            <h3>Nombre de personnes</h3>
            <button class="filter-btn" data-filter="personne" data-value="2">2 pers</button>
            <button class="filter-btn" data-filter="personne" data-value="4">4 pers</button>
            <button class="filter-btn" data-filter="personne" data-value="6">6 pers</button>
        </div>
        
        <div class="filtres-section">
            <h3>Régime alimentaire</h3>
            <button class="filter-btn" data-filter="regime" data-value="Classique">Classique</button>
            <button class="filter-btn" data-filter="regime" data-value="Vegan">Vegan</button>
            <button class="filter-btn" data-filter="regime" data-value="Sans porc">Sans porc</button>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <button class="valider-btn" id="valider-btn">Valider</button>
            
        </div>
    </div>
    
<!-- MENU CARDS -->

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
                            <span><?php echo htmlspecialchars($menu['personne']); ?> personnes minimum</span>
                        </div>
                        <div class="details-badge">
                            <a href="/code/php/detailMenu.php?id=<?php echo $menu['menu_id']; ?>"><i class="fa-solid fa-cart-shopping"></i></a>
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

<!-- FOOTER -->
 
    <div class="menu_footer">
        <p>Chaque menu impose un nombre de personnes minimum afin d'être commandé. Tous nos prix sont indiqués par personne et TTC.</p>
    </div>
    
    <script src="/code/js/filterScript.js?v=<?php echo time(); ?>"></script>
</body>
</html>
