<?php
session_start();
require_once __DIR__ . '/config.php'; 
require_once __DIR__ . '/../process/service_mail.php';

$error = '';

// POST FORMULAIRE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : null;
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : null;
    $code_postal = isset($_POST['code_postal']) ? trim($_POST['code_postal']) : null;
    $ville = isset($_POST['ville']) ? trim($_POST['ville']) : null;
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    
// VALIDATION 

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Les champs nom, prénom, email et mot de passe sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } elseif (strlen($password) < 10) {
        $error = "Le mot de passe doit contenir au moins 10 caractères.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Le mot de passe doit contenir au moins une majuscule.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Le mot de passe doit contenir au moins une minuscule.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins un chiffre.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins un caractère spécial.";
    } elseif ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {

// VERIF EMAIL

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Cet e-mail est déjà utilisé.";
        } else {

// HASH MDP

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
// INSERT USER

            try {
                $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, telephone, adresse, code_postal, ville, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user')");
                $stmt->execute([$nom, $prenom, $email, $hashedPassword, $telephone, $adresse, $code_postal, $ville]);

// EMAIL DE BIENVENUE 

                $html = "
                <div style='font-family:Arial;max-width:600px;margin:auto;'>
                <h2 style='color:#2d5f3f;'>Bienvenue chez Vite et Gourmand! </h2>
                <p>Bonjour <strong>{$prenom}</strong>,</p>
                <p>Tu peux maintenant te connecter.</p>
                </div> ";

                sendEmailBrevo($email, "Bienvenue chez Vite et Gourmand", $html);
                
// MESSAGE SUCCES

                $_SESSION['success'] = "Inscription réussie ! Connectez-vous pour continuer.";

//REDIRECTION CONNEXION

                header('Location: connexion.php');
                exit(); 
                
            } catch (PDOException $e) {
                $error = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/code/css/inscript-inform.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    
    <title>inscription</title>
</head>

<body>
   <?php include"header.php";?>
    <div class="image-container">
        <img src="/ressources/ban.png" alt="image de plat du restaurant">
       
    </div>

<!-- MESSAGE ERREUR -->

        <?php if (!empty($error)) : ?>
            <div class="error" id="errorMsg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>


<!-- FORMULAIRE -->

        <section class="form-container">

        <form class="contact-form" action="" method="POST">

            <div class="container">
                <h2>S'INSCRIRE</h2>
            </div>

            <div>
                <div>
                    <label for="nom">Nom </label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" 
                           value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                </div>
                <div>
                    <label for="prenom">Prénom </label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom"
                           value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
                </div>
            </div>

            <div>
                <label for="email">E-mail </label>
                <input type="email" id="email" name="email" placeholder="Votre E-mail"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>

            <div>
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78"
                       value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>"required>
            </div>

            <div>
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" placeholder="Votre adresse complète"
                       value="<?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?>"required>
            </div>

            <div>
                <label for="code_postal">Code postal</label>
                <input type="text" id="code_postal" name="code_postal" placeholder="Votre code postal"
                       value="<?php echo isset($_POST['code_postal']) ? htmlspecialchars($_POST['code_postal']) : ''; ?>"required>
            </div>

            <div>
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" placeholder="Votre ville"
                       value="<?php echo isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : ''; ?>"required>
            </div>

<!-- PASSWORD -->
 
            <div class="password-container">
                <label for="password">Votre mot de passe </label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                    <i class="fa-solid fa-eye" id="toggleIcon1"></i>
                </button>
            </div>

            <p>(Le mot de passe doit contenir 10 caractères minimum, contenir au minimum un chiffre, un caractère spécial, une majuscule, une minuscule.)</p>

            <div class="password-container">
                <label for="confirm-password">Confirmez votre mot de passe </label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmation" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', 'toggleIcon2')">
                    <i class="fa-solid fa-eye" id="toggleIcon2"></i>
                </button>
            </div>

            <div>
                <button type="submit" class="submit-btn">S'INSCRIRE</button>
            </div>
            
        </form>
    </section>
     
       <script src="/code/js/password.js?v=<?php echo time(); ?>"></script>
   
</body>

</html>