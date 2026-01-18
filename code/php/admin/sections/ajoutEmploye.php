<?php
session_start();
require_once './../../config.php';
require_once './../../../process/service_mail.php';

$error = '';
$success = '';

// FORMULAIRE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : null;
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : null;
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

        // VERIFIER SI EMAIL EXISTE

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Cet e-mail est déjà utilisé.";
        } else {

            // HASH MDP

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // EMPLOYÉ DANS BDD

            try {
                $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, telephone, adresse, role) VALUES (?, ?, ?, ?, ?, ?, 'employee')");
                $stmt->execute([$nom, $prenom, $email, $hashedPassword, $telephone, $adresse]);
                
                // EMAIL EMPLOYÉ

                $subject = "Bienvenue chez Vite et Gourmand";
                $htmlContent = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #2d6a4f; color: white; padding: 20px; text-align: center; border-radius: 5px; }
                        .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; border-radius: 5px; }
                        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
                        .highlight { background-color: #d1f4e0; padding: 10px; }
                        .userMail { background-color: #fff; padding: 15px; border: 2px solid #2d6a4f; border-radius: 5px; margin: 15px 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Vite et Gourmand</h2>
                        </div>
                        <div class='content'>
                            <p>Bonjour " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . ",</p>
                            <div class='highlight'>
                                <p>Bienvenue dans l'équipe de <strong>Vite et Gourmand</strong> !</p>
                                <p>Votre compte employé a été créé avec succès.</p>
                            </div>
                            <div class='userMail'>
                                <p><strong>Votre nom d'utilisateur :</strong></p>
                                <p style='font-size: 16px; color: #2d6a4f; font-weight: bold;'>" . htmlspecialchars($email) . "</p>
                            </div>
                            <p>Vous pouvez maintenant vous connecter à votre espace employé avec ce nom d'utilisateur et le mot de passe qui vous a été communiqué par votre responsable.</p>
                            <p>À très bientôt !</p>
                        </div>
                        <div class='footer'>
                            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                sendEmailBrevo($email, $subject, $htmlContent);
                
                $_SESSION['success'] = "L'employé a été ajouté avec succès et un email de confirmation lui a été envoyé !";
                header("Location: ../dashboard.php");
                exit;
                
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
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/code/css/ajoutEmployés.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <title>ajouter un employé</title>
</head>

<body>
    <?php include "../admin_header.php"; ?>
    
   

    <section class="form-container">
        <form class="contact-form" method="POST" action="">
            <div class="container">
                <h1>AJOUTER UN EMPLOYÉ</h1>
            </div>

            <div>
                <div>
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" placeholder="Son nom" 
                           value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
                </div>
                <div>
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Son prénom"
                           value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
                </div>
            </div>

            <div>
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" placeholder="Son E-mail"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>

            <div>
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78"
                       value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>" required>
            </div>

            <div>
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" placeholder="Son adresse complète"
                       value="<?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?>" required>
            </div>

            <!-- PASSWORD -->
             
            <div class="password-container">
                <label for="password">Son mot de passe *</label>
                <input type="password" id="password" name="password" placeholder="Entrez un mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                    <i class="fa-solid fa-eye" id="toggleIcon1"></i>
                </button>
            </div>

            <p>(Le mot de passe doit contenir 10 caractères minimum, contenir au minimum un chiffre, un caractère spécial, une majuscule, une minuscule.)</p>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="password-container">
                <label for="confirm-password">Confirmez son mot de passe *</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez son mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', 'toggleIcon2')">
                    <i class="fa-solid fa-eye" id="toggleIcon2"></i>
                </button>
            </div>

            <div>
                <button type="submit" class="submit-btn">CRÉER</button>
            </div>
        </form>
    </section>
     
    <script src="/code/js/password.js?v=<?php echo time(); ?>"></script>
   
</body>

</html>