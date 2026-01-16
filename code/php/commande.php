
<?php
session_start();
require_once 'config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: /code/php/connexion.php');
    exit;
}

//SELECT INFOS COMMANDE

$stmt = $pdo->prepare("
    SELECT c.*, 
           m.titre, m.image,
           u.nom, u.prenom, u.email, u.telephone, u.adresse, u.code_postal, u.ville
    FROM commandes c
    INNER JOIN menus m ON c.menu_id = m.menu_id
    INNER JOIN users u ON c.user_id = u.id
    WHERE c.user_id = :user_id
    ORDER BY c.id DESC
    
");

$stmt->execute([':user_id' => $_SESSION['user_id']]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);


// MESSAGE SUCCÉS

$success_message = null;
if (!empty($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/commande.css?v=<?php echo time(); ?>">
    <title>Ma commande</title>
</head>
<body>
    <?php include "header.php"; ?>

    <?php if (isset($success_message)): ?>
        <div class="success-message" id="successMsg">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php foreach ($commandes as $commande):
      $peut_modifier = ($commande['statut'] === 'en_attente');?>

    <div class="overlay" id="overlay-<?php echo $commande['id']; ?>"></div>

    <nav class="follow-menu" id="followMenu-<?php echo $commande['id']; ?>">
        <h2>SUIVI</h2>
        <p>Votre commande est actuellement</p>
        <div class="status-badge status-<?php echo strtolower($commande['statut']); ?>">
            <p><?php echo strtoupper(str_replace('_', ' ', $commande['statut'])); ?></p>
        </div>
       
    </nav>

    <div class="container">

<!-- COMMANDE -->

        <div class="card left-card">
            <div class="card-header">Votre commande #<?php echo $commande['id']; ?></div>
            <div class="content">
                <div class="dish-container">
                    <div class="dish-card">
                        <h3><?php echo htmlspecialchars($commande['titre']); ?></h3>
                        <img src="/<?php echo htmlspecialchars($commande['image']); ?>" 
                             alt="<?php echo htmlspecialchars($commande['titre']); ?>">
                    </div>
                </div>
            </div>
        </div>

<!-- INFORMATIONS CLIENT -->

        <div class="card right-card">
            <div class="card-header">Vos informations</div>
            <div class="form-content">
                <form id="orderForm-<?php echo $commande['id']; ?>" method="POST" action="../process/modifier_commande.php">
                    <input type="hidden" name="commande_id" value="<?php echo $commande['id']; ?>">

                    <div class="form-group">
                        <label for="nom-<?php echo $commande['id']; ?>">Nom</label>
                        <input type="text" id="nom-<?php echo $commande['id']; ?>" name="nom" 
                               value="<?php echo htmlspecialchars($commande['nom']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="prenom-<?php echo $commande['id']; ?>">Prénom</label>
                        <input type="text" id="prenom-<?php echo $commande['id']; ?>" name="prenom" 
                               value="<?php echo htmlspecialchars($commande['prenom']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email-<?php echo $commande['id']; ?>">Email</label>
                        <input type="email" id="email-<?php echo $commande['id']; ?>" name="email" 
                               value="<?php echo htmlspecialchars($commande['email']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="telephone-<?php echo $commande['id']; ?>">Téléphone</label>
                        <input type="tel" id="telephone-<?php echo $commande['id']; ?>" name="telephone" 
                               value="<?php echo htmlspecialchars($commande['telephone']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="adresse-<?php echo $commande['id']; ?>">Adresse</label>
                        <input type="text" id="adresse-<?php echo $commande['id']; ?>" name="adresse" 
                               value="<?php echo htmlspecialchars($commande['adresse']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="code_postal-<?php echo $commande['id']; ?>">Code postal</label>
                        <input type="text" id="code_postal-<?php echo $commande['id']; ?>" name="code_postal" 
                               value="<?php echo htmlspecialchars($commande['code_postal']); ?>">
                    </div>

                      <div class="form-group">
                        <label for="ville-<?php echo $commande['id']; ?>">Ville</label>
                        <input type="text" id="ville-<?php echo $commande['id']; ?>" name="ville" 
                               value="<?php echo htmlspecialchars($commande['ville']); ?>">
                    </div>

                    

                    <div class="form-group">
                        <label for="personnes-<?php echo $commande['id']; ?>">Nombre de personnes</label>
                        <input type="text" id="personnes-<?php echo $commande['id']; ?>" name="personnes" 
                               value="<?php echo $commande['nb_personnes']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="date-<?php echo $commande['id']; ?>">Date de livraison</label>
                        <input type="date" id="date-<?php echo $commande['id']; ?>" name="date" 
                               value="<?php echo $commande['date_livraison']; ?>" 
                               <?php echo !$peut_modifier ? 'readonly' : ''; ?>
                               min="<?php echo date('Y-m-d', strtotime('+6 days')); ?>">
                    </div>

                    <div class="form-group">
                        <label for="heure-<?php echo $commande['id']; ?>">Heure de livraison</label>
                        <input type="time" id="heure-<?php echo $commande['id']; ?>" name="heure" 
                               value="<?php echo date('H:i', strtotime($commande['heure_livraison'])); ?>" 
                               <?php echo !$peut_modifier ? 'readonly' : ''; ?>
                               min="09:00" max="20:00">
                    </div>

                    <div class="form-group">
                        <label for="prix-<?php echo $commande['id']; ?>">Prix total</label>
                        <input type="text" id="prix-<?php echo $commande['id']; ?>" name="prix" 
                               value="<?php echo number_format($commande['total'], 2); ?> €" 
                               readonly>
                    </div>

                    <div class="button-container">
                        <?php if ($peut_modifier): ?>
                            <button type="submit" class="btn btn-modify" name="action" value="modifier">MODIFIER</button>
                            <button type="submit" class="btn btn-cancel" name="action" value="annuler" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">ANNULER</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-disabled" disabled>MODIFICATION IMPOSSIBLE</button>
                            <p class="info-modification">Votre commande a déjà été validée</p>
                        <?php endif; ?>
                        <button type="button" class="btn btn-follow" data-commande-id="<?php echo $commande['id']; ?>">SUIVRE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <script src="/code/js/suivi.js?v=<?php echo time(); ?>"></script>
    <script>
        
        setTimeout(function() {
            const msg = document.getElementById('successMsg');
            if (msg) {
                msg.style.display = 'none';
            }
        }, 3000);
    </script>
</body>
</html>
