<?php
session_start();
require_once '../../config.php'; 

// VERIF ROLE

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: /code/php/connexion.php');
    exit;
}

if ($_SESSION['role'] === 'admin') {
    include '../admin_header.php';
} elseif ($_SESSION['role'] === 'employee') {
    include '../../employee/employe_header.php';
}

// REQUETE

$query = " SELECT c.*, 
           u.nom AS user_nom, 
           u.prenom AS user_prenom, 
           u.telephone AS user_tel,
           u.code_postal AS user_code_postal,
           u.ville AS user_ville,
           m.titre AS menu_titre
    FROM commandes c
    INNER JOIN users u ON c.user_id = u.id
    INNER JOIN menus m ON c.menu_id = m.menu_id
    ORDER BY c.id DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/gestionCommandes.css?v=<?php echo time(); ?>">
    <title>Gestion des commandes</title>
</head>
<body>

  <?php 

// MESSAGE SUCCÉS


if (isset($_SESSION['success'])) {
    echo '<div class="success" id="successMsg">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
    ?>
    <script>
        setTimeout(function() {
            document.getElementById('successMsg').style.display = 'none';
        }, 3000);
    </script>
    <?php
}
?>
    
    <div class="btn-container">
        <button class="main-btn">GESTION DES COMMANDES</button>
    </div>
    <div>
        <h3>CONTACTER LE CLIENT AVANT D'ANNULER SA COMMANDE</h3>
    </div>
    <div class="container">
        <div class="left-card">
            <div class="card-header">
                <h2 class="card-title">Commandes</h2>
            </div>
            <div class="user-list">
                <?php foreach ($commandes as $commande): ?>
                    <div class="user-item" data-commande-id="<?php echo $commande['id']; ?>">
                        <div class="user-info">
                            
                                <span class="user-name">
                                    <strong style="color: #6ec585;">
                                        <?php echo htmlspecialchars($commande['user_prenom'] . ' ' . $commande['user_nom']); ?>
                                    </strong><br>

                                    <strong>Menu :</strong> <?php echo htmlspecialchars($commande['menu_titre']); ?><br>
                                    <strong>Nombre de personnes :</strong> <?php echo htmlspecialchars($commande['nb_personnes']); ?><br>
                                    <strong>Total :</strong> <?php echo htmlspecialchars($commande['total']); ?> €<br>
                                    <strong>Téléphone :</strong> <?php echo htmlspecialchars($commande['user_tel']); ?><br>
                                    <strong>Adresse :</strong> <?php echo htmlspecialchars($commande['adresse_livraison']); ?><br>
                                    <strong>Code postal :</strong> <?php echo htmlspecialchars($commande['code_postal']); ?><br>
                                    <strong>Ville :</strong> <?php echo htmlspecialchars($commande['ville']); ?><br>
                                    <strong>Date de livraison :</strong> 
                                    <?php 
                                        $date = new DateTime($commande['date_livraison']);
                                        echo $date->format('d/m/Y') . ' ' . htmlspecialchars($commande['heure_livraison']);
                                    ?>
                                </span>
                        </div>

<!--MOTIF ANNULATION-->  

                        <div class="motif-annulation-column">
                            <?php if (!empty($commande['motif_annulation'])): ?>
                                <div class="motif-annulation-box">
                                    <strong class="motif-title-small">Motif d'annulation :</strong>
                                    <p class="motif-text"><?php echo htmlspecialchars($commande['motif_annulation']); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="motif-empty">-</div>
                            <?php endif; ?>
                        </div>

                        <div class="status-badge">
                            <?php echo ucfirst($commande['statut']); ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<!-- MENU STATUTS -->

    <div class="right-card">
        <div class="right-card-header">
            <h3 class="right-card-title">Statuts</h3>
        </div>
        
        <a href="#" class="status-item" id="status-acceptee" data-status="acceptee">Acceptée</a>
        <a href="#" class="status-item" id="status-preparation" data-status="preparation">Préparation</a>
        <a href="#" class="status-item" id="status-livraison" data-status="livraison">En livraison</a>
        <a href="#" class="status-item" id="status-livree" data-status="livree">Livrée</a>
        <a href="#" class="status-item" id="status-retour" data-status="retour">Retour</a>
        <a href="#" class="status-item" id="status-terminee" data-status="terminee">Terminée</a>
        <a href="#" class="status-item" id="status-annulee" data-status="annulee">Annulée</a>
    </div>

<!-- FENETRE ANNULATION -->

    <div class="motif-container" id="motif-container">
        <div class="motif-header">
            <h3 class="motif-title">Motif d'annulation</h3>
        </div>
        
        <textarea id="motif-annulation" class="motif-textarea" placeholder="Indiquez la raison de l'annulation..." rows="6"></textarea>
        <button id="btn-envoyer-annulation" class="btn-envoyer-annulation">Envoyer</button>
    </div>
   
    <div id="overlay" class="overlay"></div>

    <script src="/code/js/gestionStatuts.js?v=<?php echo time(); ?>"></script>
</body>
</html>