   <?php 
    session_start();
    include 'header.php';
    
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


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/code/css/connexion.css?v=<?php echo time(); ?>">
    <title>Connexion</title>
</head>
<body>
 
    
    <div>
        <img src="/ressources/ban.png" alt="image de plat du restaurant">
    </div>
    
    <section class="form-container">
        
<!--POST VERS PROCESS-->

        <form class="contact-form" method="POST" action="../process/process_login.php">
            <div class="container">
                <h2>SE CONNECTER</h2>
            </div>
            
            <div>
                <label for="email">Votre E-mail</label>
                <input type="email" id="email" name="email" placeholder="E-mail"required>
            </div>
            
             <div class="password-container">
                <label for="password">Votre mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                    <i class="fa-solid fa-eye" id="toggleIcon1"></i>
                </button>
            </div>
            
            <div class="links-container">
                <div class="password-link">
                    <a href="/code/php/reinitMDP.php">Mot de passe oublié ?</a>
                </div>
                <div class="inscription-link">
                    <a href="/code/php/inscription.php">Pas encore inscrit ?</a>
                </div>
            </div>
            
            <div>
                <button type="submit"class="submit-btn">CONNEXION</button>
            </div>
        </form>
    </section>
    <script src="/code/JS/password.js?v=<?php echo time(); ?>"></script>
</body>
</html>