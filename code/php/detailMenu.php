<?php
session_start();
include 'config.php';

// GET ID MENU
$menu_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($menu_id <= 0) {
    header('Location: /code/php/menus.php');
    exit;
}

// SELECT INFOS MENU

$query = "SELECT theme, titre, regime, prix, personne, description FROM menus WHERE menu_id = :menu_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt->execute();
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header('Location: /code/php/menus.php');
    exit;
}

// Gestion du clic sur "Commander"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commander'])) {
    // Sauvegarder le menu dans la session
    $_SESSION['commande_en_cours'] = [
        'menu_id' => $menu_id,
        'menu_info' => $menu,
        'timestamp' => time()
    ];
    
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {

// REDIRECTION CONNEXION

        header('Location: /code/php/connexion.php');
        exit;
    } else {

// REDIRECTION PANIER

        header('Location: /code/php/panier.php');
        exit;
    }
}

// SELECT ALLERGENES MENU

$query_allergenes = "SELECT a.nom
                     FROM menu_allergenes ma
                     INNER JOIN allergenes a ON ma.allergenes_id = a.id
                     WHERE ma.menu_id = :menu_id
                     ORDER BY a.nom";

$stmt_allergenes = $pdo->prepare($query_allergenes);
$stmt_allergenes->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt_allergenes->execute();
$allergenes = $stmt_allergenes->fetchAll(PDO::FETCH_COLUMN);

// SELECT PLATS MENU

$query_plates = "SELECT p.name, p.image, c.name AS categories
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
        <link rel="stylesheet" href="/code/css/detailMenu.css?v=<?php echo time(); ?>">
        <title>Detail menu</title>
    </head>

    <body>  
    
        <?php include 'header.php';?>

        <div class="secondMenu">
            <p>Régime : <?php echo htmlspecialchars($menu['regime']); ?></p>
            <p><?php echo number_format($menu['prix'], 0); ?>€ (<?php echo $menu['personne']; ?> pers)</p>
            <?php if (!empty($allergenes)): ?>
                <button id="allergieBtn">Liste des allergènes</button>
            <?php endif; ?>
        </div>
        
<!-- ALLERGENES -->

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
        
<!-- TITRES -->    

        <div class="theme">
            <p class="titre"><?php echo htmlspecialchars($menu['theme']); ?></p>
        </div>
        
        <h4>"<?php echo htmlspecialchars($menu['titre']); ?>"</h4>
        <p><?php echo htmlspecialchars($menu['description']); ?></p>

<!-- PLATS -->

        <div id="cardsContainer">
            <?php foreach ($plates as $plate): ?>
                <div class="card">
                    <div class="card-header">
                        <?= nl2br(htmlspecialchars($plate['name'])) ?>
                    </div>

                    <div class="image-container">
                        <img src="/<?= htmlspecialchars($plate['image']) ?>" 
                            alt="<?= htmlspecialchars($plate['name']) ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    
<!-- FOOTER -->

        <footer>
            <div class="footer-left">
                <div class="footer-message">
                    <p>Ce menu doit être commandé &nbsp<strong>6 jours minimum</strong>&nbsp avant la date de la prestation.</p>
                </div>
                <div class="footer-info">
                    <p>Tous nos plats sont fabriqués à base de produits frais.<br>
                    Ils doivent être conservés au réfrigérateur et peuvent être congelés.</p>
                </div>
            </div>
            <div class="footer-right">
                <form method="POST" action="">
                    <button type="submit" name="commander" class="btn-commander">COMMANDER</button>
                </form>

<!-- LINKS -->
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="auth-links">
                        <a href="/code/php/connexion.php" class="btn-auth">Se connecter</a>
                        <span class="separator">/</span>
                        <a href="/code/php/inscription.php" class="btn-auth">S'inscrire</a>
                    </div>
                <?php endif; ?>
            </div>
        </footer>

<!-- OVERLAY -->
        
        <div class="overlay" id="overlay"></div>

        <script src="/code/js/allergie.js?v=<?php echo time(); ?>"></script>
    </body>
</html>