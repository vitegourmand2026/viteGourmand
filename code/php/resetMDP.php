<?php
session_start();
require_once __DIR__ . '/config.php'; 

$success = '';
$error = '';
$step = 1; //NECESSAIRE ?
$user_id = null;

// VERIF POST CODE

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];
    $email = $_POST['email'];
    
    try {

// VERIF CODE

        $stmt = $pdo->prepare("SELECT id, prenom, nom FROM users WHERE email = ? AND reset_code = ? AND role = 'user'");
        $stmt->execute([$email, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $user_id = $user['id'];
            $step = 2; // NECESSAIRE ?
        } else {
            $error = "Code invalide ou email incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

// VERIF POST MDP

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && isset($_POST['user_id'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $user_id = $_POST['user_id'];
    
// VALIDATION

    if (empty($password)) {
        $error = "Le champ mot de passe est obligatoire.";
        $step = 2;
    } elseif (strlen($password) < 10) {
        $error = "Le mot de passe doit contenir au moins 10 caractères.";
        $step = 2;
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Le mot de passe doit contenir au moins une majuscule.";
        $step = 2;
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Le mot de passe doit contenir au moins une minuscule.";
        $step = 2;
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins un chiffre.";
        $step = 2;
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins un caractère spécial.";
        $step = 2;
    } elseif ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
        $step = 2;
    } else {

// HASH MDP

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
// UPDATE MDP

        try {
            $stmt = $pdo->prepare("UPDATE users SET mot_de_passe = ?, reset_code = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user_id]);
            
            $success = "Votre mot de passe a été réinitialisé avec succès !";

            unset($_SESSION['reset_email']);
            
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
            $step = 2;
        }
        
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/code/css/inscript-inform.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="icon" type="image/png" href="/ressources/icons/toque.png">
<title>Changement mot de passe</title>
</head>
<body>
<?php include "header.php"; ?>
<div>
<img src="/ressources/ban.png" alt="image de plat du restaurant">
</div>
<section class="form-container">
<form class="contact-form" action="" method="POST">
<div class="container">
<h1>NOUVEAU MOT DE PASSE</h1>
</div>

<?php
if ($success) {
    $_SESSION['success'] = $success; 
    header('Location: /code/php/index.php'); 
    exit; 
} elseif ($error) {
    ?>
    <div class="message error">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php
}
?>

<?php if (!$success): ?>
    <?php if ($step === 1): ?>
    
            <p>Entrez le code à 6 chiffres reçu par email et votre adresse e-mail.</p>
            
            <div>
                <label for="email">Votre E-mail</label>
                <input type="email" name="email" value="<?php echo isset($_SESSION['reset_email']) ? htmlspecialchars($_SESSION['reset_email']) : ''; ?>" required>

            </div>
            
            <div>
                <label for="code">Code de vérification</label>
                <input type="text" id="code" name="code" placeholder="123456" maxlength="6" pattern="[0-9]{6}" required>
            </div>
            
            <div>
                <button type="submit" class="submit-btn">VÉRIFIER</button>
            </div>
            
         <?php else: ?>
    
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
    
            <div class="password-container">
                <label for="password">Votre nouveau mot de passe </label>
                <input type="password" id="password" name="password" placeholder="Entrez votre nouveau mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                    <i class="fa-solid fa-eye" id="toggleIcon1"></i>
                </button>
            </div>
            <p>(Le mot de passe doit contenir 10 caractères minimum, contenir au minimum un chiffre, un caractère spécial, une majuscule, une minuscule.)</p>

            <div class="password-container">
                <label for="confirm-password">Confirmez votre mot de passe </label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez votre nouveau mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', 'toggleIcon2')">
                    <i class="fa-solid fa-eye" id="toggleIcon2"></i>
                </button>
            </div>

            <div>
                <button type="submit" class="submit-btn">VALIDER</button>
            </div>
    <?php endif; ?>
<?php endif; ?>
</form>
</section>
<script src="/code/js/password.js?v=<?php echo time(); ?>"></script>
</body>
</html>