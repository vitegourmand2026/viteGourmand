//  Interface administrateur
<?php 
session_start();
require_once '../../process/check_auth.php';  

// VERIF ROLE ADMIN

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');  
    exit();
}
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



<!DOCTYPE html>
<html>
<head>
     
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/code/css/admin.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <title>Espace admin</title>
</head>
   
<?php include"admin_header.php";?>

<body>

<!-- MENU ADMIN -->
    
    <div class="menuContainer">
        <a href="/code/php/admin/sections/gestionMenus.php" class="menu-btn">Gestion des menus</a>
        <a href="/code/php/admin/sections/gestionCommandes.php" class="menu-btn">Gestion des commandes</a>
        <a href="/code/php/admin/sections/gestionAvis.php" class="menu-btn">Gestion des avis</a>

        <a href="#" class="menu-btn" id="timeBtn">Gestion des horaires</a> 

        <a href="/code/php/admin/sections/gestionEmployés.php" class="menu-btn">Gestion des employés</a>
        <a href="/code/php/admin/sections/statistiques.php" class="menu-btn">Statistiques</a>
        <a href="/code/php/admin/sections/messages.php" class="menu-btn">Messages</a>
        <a href="/code/process/logout.php" class="menu-btn"><i class="fa-solid fa-power-off"></i>Deconnexion</a>
    </div>

    <nav class="time-menu" id="timeMenu">
    <h2>HORAIRES</h2>
    <form method="POST" action="/code/process/update_horaires.php">
        <div>
            <label for="horaires"></label>
            <textarea id="horaires" name="horaires" placeholder="Nouveaux horaires" rows="4" required></textarea>
        </div>
        <div>
            <button type="submit" class="valider-btn">VALIDER</button>
        </div>
    </form>
</nav>
    <div class="overlay" id="overlay"></div>
    <script src="/code/js/horaires.js?v=<?php echo time(); ?>"></script>
   
</body>

</html>

