<?php
session_start();
require_once 'config.php';


// VERIF SESSION

if (!isset($_SESSION['user_id'])) {
    header('Location: /code/php/connexion.php');
    exit;
}

// VEROF COMMANDE

if (!isset($_SESSION['commande_en_cours'])) {
    header('Location: /code/php/menus.php');
    exit;
}

// SELECT MENU ID

$menu_id = $_SESSION['commande_en_cours']['menu_id'];

$query = "SELECT menu_id, titre, prix, personne, image 
          FROM menus 
          WHERE menu_id = :menu_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt->execute();
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

// SELECT USER

$user_id = $_SESSION['user_id'];
$query = "SELECT nom, prenom, email, telephone, adresse, code_postal, ville FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// MAJ PERSONNES

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nb_personnes'])) {
    $nb_personnes = (int)$_POST['nb_personnes'];
    
// VERIF MINIMUM PERSONNES

    if ($nb_personnes >= (int)$menu['personne']) {

// CALCUL PRIX

        $prix_menu_total = $menu['prix'] * $nb_personnes;
        $frais_livraison = 5.00;
        $total = $prix_menu_total + $frais_livraison;
        
// MAJ SESSION

        $_SESSION['commande_en_cours']['nb_personnes'] = $nb_personnes;
        $_SESSION['commande_en_cours']['prix_menu'] = $menu['prix'];
        $_SESSION['commande_en_cours']['prix_total'] = $prix_menu_total;
        $_SESSION['commande_en_cours']['frais_livraison'] = $frais_livraison;
        $_SESSION['commande_en_cours']['total'] = $total;
    }
}

// RECUP NB PERSONNES

$nb_personnes = isset($_SESSION['commande_en_cours']['nb_personnes']) 
    ? $_SESSION['commande_en_cours']['nb_personnes'] 
    : (int)$menu['personne'];

// CALCUL PRIX (2FOIS??)

$prix_menu_total = $menu['prix'] * $nb_personnes;
$frais_livraison = 5.00;
$total = $prix_menu_total + $frais_livraison;

// VERIF SESSION

if (!isset($_SESSION['commande_en_cours']['nb_personnes'])) {
    $_SESSION['commande_en_cours']['nb_personnes'] = $nb_personnes;
    $_SESSION['commande_en_cours']['prix_menu'] = $menu['prix'];
    $_SESSION['commande_en_cours']['prix_total'] = $prix_menu_total;
    $_SESSION['commande_en_cours']['frais_livraison'] = $frais_livraison;
    $_SESSION['commande_en_cours']['total'] = $total;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/panier.css?v=<?php echo time(); ?>">
    <title>Panier</title>
</head>
<body>
    <?php include "header.php"; ?>
    
    <div class="container">

<!-- COMMANDE -->
        
        <div class="card left-card">
            <div class="header">Votre commande</div>
                <div class="content">
                    <div class="dish-container">
                        <div class="dish-card">
                            <h3><?php echo htmlspecialchars($menu['titre']); ?></h3>
                            <img src="/<?php echo htmlspecialchars($menu['image']); ?>" 
                                alt="<?php echo htmlspecialchars($menu['titre']); ?>">
                            <p class="menu-info">
                                <?php echo $menu['personne']; ?> personnes minimum
                            </p>
                        </div>

<!-- POST NB PERSONNES -->
                        
                        <form method="POST" id="form-personnes">
                            <div class="input-group">
                                <i class="fa-regular fa-circle-user"></i>
                                <input 
                                    type="number" 
                                    id="nb_personnes" 
                                    name="nb_personnes" 
                                    min="<?php echo (int)$menu['personne']; ?>" 
                                    value="<?php echo $nb_personnes; ?>" required 
                                    data-prix="<?php echo $menu['prix']; ?>"
                                    data-min="<?php echo (int)$menu['personne']; ?>"
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                        
                        <p>Veuillez choisir le nombre de personnes en respectant le minimum indiqué, merci.</p>
                    </div>
                </div>
        </div>

<!-- PANIER -->
        
        <div class="card right-card">
            <div class="header">Total</div>

            <div class="form-group">
                <label>Sous-total :</label>
                <span class="price" id="sous_total"><?php echo number_format($prix_menu_total, 2); ?> €</span>
            </div>

            <div class="form-group">
                <label>Livraison :</label>
                <span class="price" id="frais_livraison"><?php echo number_format($frais_livraison, 2); ?> €</span>
            </div>

            <hr>

            <div class="form-group total-group">
                <label><strong>Total :</strong></label>
                <span class="price total-price" id="total">
                    <strong><?php echo number_format($total, 2); ?> €</strong>
                </span>
            </div>

            <div class="payment">
                <p>Le paiement sera effectué au moment de la livraison, nous acceptons :</p>
                <img src="/ressources/moyens-paiement.png"
                    alt="Différents moyens de paiement : carte bleue, visa, american express">
            </div>
            <p class="messageInfos">Veuillez remplir les informations de livraison dans le formulaire ci-dessous.
                <br>Les frais de livraison seront calculés automatiquement aprés avoir saisi votre adresse de livraison.</p>
            </p>
        </div>
    </div>
    
    <section class="form-container">

    <!-- Affichage des messages d'erreur A REFAIRE-->
    <?php if (isset($_SESSION['error'])): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; margin:20px; border-radius:5px; border:1px solid #f5c6cb;">
            <strong>❌ Erreur :</strong> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Affichage des messages de succès A REFAIRE -->
    <?php if (isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin:20px; border-radius:5px; border:1px solid #c3e6cb;">
            <strong>✓ Succès :</strong> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

<!-- FORMAULAIRE LIVRAISON -->
    
    <form action="/code/process/valider_commande.php" method="POST" class="contact-form">
        <h2>VOS INFORMATIONS POUR LA LIVRAISON</h2>

        <label for="nom">Nom </label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" required>

        <label for="prenom">Prénom </label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" required>

        <label for="email">E-mail </label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

        <label for="telephone">Téléphone </label>
        <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>" required>

        <label for="adresse">Adresse de livraison *</label>
        <input type="text" id="adresse" name="adresse_livraison" value="<?php echo htmlspecialchars($user['adresse'] ?? ''); ?>" required>

        <label for="code_postal">Code postal </label>
        <input type="text" id="code_postal" name="code_postal" value="<?php echo htmlspecialchars($user['code_postal'] ?? ''); ?>" required>

        <label for="ville">Ville </label>
        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($user['ville'] ?? ''); ?>" required>

        <label for="date">Date de livraison </label>
        <input type="date" id="date" name="date" min="<?php echo date('Y-m-d', strtotime('+6 days')); ?>" required>

        <label for="heure">Heure de livraison *</label>
        <input type="time" id="heure" name="heure" min="09:00" max="20:00" required>

<!-- HIDDEN FIELDS -->
 
        <input type="hidden" name="menu_id" value="<?php echo $_SESSION['commande_en_cours']['menu_id']; ?>">
        <input type="hidden" name="nb_personnes" value="<?php echo $_SESSION['commande_en_cours']['nb_personnes']; ?>">
        <input type="hidden" name="sous_total" value="<?php echo $_SESSION['commande_en_cours']['prix_total']; ?>">
        <input type="hidden" name="frais_livraison" value="<?php echo $_SESSION['commande_en_cours']['frais_livraison']; ?>">
        <input type="hidden" name="total" value="<?php echo $_SESSION['commande_en_cours']['total']; ?>">

        <button type="submit" class="submit-btn">VALIDER</button>
    </form>
</section>

    <footer>
        
    </footer>
    <script src="../js/calculLivraison.js?v=<?php echo time(); ?>"> </script>
    <script src="../js/updatePrices.js?v=<?php echo time(); ?>"> </script>
</body>
</html>