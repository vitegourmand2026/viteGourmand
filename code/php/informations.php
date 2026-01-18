<?php
session_start();
require_once __DIR__ . '/config.php'; 

$error = '';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$userId = $_SESSION['user_id'];

// SELECT USER

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'user'");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non autorisé.");
}

// POST FORMULAIRE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : null;
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : null;
    $code_postal = isset($_POST['code_postal']) ? trim($_POST['code_postal']) : null;
    $ville = isset($_POST['ville']) ? trim($_POST['ville']) : null;
    
    
// VALIDATION

    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = "Les champs nom, prénom, email sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } else {

// VERIF EMAIL

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        
        if ($stmt->fetch()) {
            $error = "Cet e-mail est déjà utilisé.";
        } else { 

// UPDATE BDD

            try {
                $stmt = $pdo->prepare("
                UPDATE users 
                SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, code_postal = ?, ville = ?
                WHERE id = ?
");
                $stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $userId]);
                
// MESSAGE SUCCES

                $_SESSION['success'] = "Vos informations ont bien été modifiées";
                
// REDIRECTION ACCUEIL

                header("Location: index.php");
                exit();
                
            } catch (PDOException $e) {
                $error = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
    }
}  
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
     <link rel="stylesheet" href="/code/css/inscript-inform.css?v=<?php echo time(); ?>">
    
    <title>Mes informations</title>
</head>

<body>
 <?php include"header.php";?>

   <div class="image-container">
        <img src="/ressources/ban.png" alt="image de plat du restaurant">
       
    </div>

<!--MESSAGE EREEUR-->

    <?php if (!empty($error)) : ?>
        <div class="error" id="errorMsg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

<!-- FORMULAIRE-->

      <section class="form-container">

        <form class="contact-form" action="" method="POST">

            <div class="container">
                <h2>MODIFIER VOS INFORMATIONS</h2>
            </div>

            <div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" 
                           value="<?php echo htmlspecialchars($user['nom']) ?>">
                </div>
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom"
                           value="<?php echo htmlspecialchars($user['prenom']); ?>">
                </div>
            </div>

            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Votre E-mail"
                       value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>

            <div>
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78"
                       value="<?php echo htmlspecialchars($user['telephone']); ?>">
            </div>

            <div>
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" placeholder="Votre adresse complète"
                       value="<?php echo htmlspecialchars($user['adresse']); ?>">
            </div>

            <div>
                <label for="code_postal">Code postal</label>
                <input type="text" id="code_postal" name="code_postal" placeholder="Votre code postal"
                       value="<?php echo htmlspecialchars($user['code_postal']); ?>">
            </div>

            <div>
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" placeholder="Votre ville"
                       value="<?php echo htmlspecialchars($user['ville']); ?>">
            </div>

          
                <button type="submit" class="submit-btn">MODIFIER</button>

            </div>
        </form>
    </section>
   
</body>

</html>