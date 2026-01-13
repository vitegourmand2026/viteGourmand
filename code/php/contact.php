<?php
session_start(); 
require_once __DIR__ . '/config.php'; 

$error = '';

// POST FORMULAIRE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
// VALIDATION

    if (empty($nom) || empty($prenom) || empty($email) || empty($message)) {
        $error = "Les champs nom, prénom, email et message sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (nom, prenom, email, message) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$nom, $prenom, $email, $message])) {

// MESSAGE SUCCÉS

                $_SESSION['success'] = "Votre message a bien été envoyé";
                
// REDIRECTION

                header('Location: index.php');
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de l'envoi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/connexion.css?v=<?php echo time(); ?>">
    <title>Contact</title>
</head>
<body>
    <?php include 'header.php';?>
    
    <div>
        <img src="/ressources/ban.png" alt="image de plat du restaurant">
    </div>
    
    <section class="form-container">

 <!-- ERREUR-->
  
        <?php if ($error): ?>
            <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

<!-- FORMULAIRE-->
        
        <form class="contact-form" method="POST">
            <div class="container">
                <h2>NOUS CONTACTER</h2>
            </div>
            
            <div>
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" 
                       value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" required>
            </div>
            
            <div>
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom"
                       value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" required>
            </div>
            
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Votre E-mail"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div>
                <label for="message">Votre message</label>
                <textarea id="message" name="message" placeholder="Votre message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>
            
            <div>
                <button type="submit"class="submit-btn">ENVOYER</button>
            </div>
        </form>
    </section>
</body>
</html>