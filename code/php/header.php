<!DOCTYPE php>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Italiana&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">

    <link rel="stylesheet" href="/code/css/header.css?v=<?php echo time(); ?>">

    


    <title>header</title>

</head>

<body>

    <header>

<!-- NAVBAR GAUCHE -->

        <div class="navbar">
            <div class="burger" id="burgerBtn">
                <i class="fa-solid fa-bars"></i>
            </div>
            <div>
                <a href="/code/php/index.php" class="header-btn">Accueil</a>
                
            </div>
            <div>
                <a href="/code/php/menus.php" class="header-btn">Menus</a>
            </div>

<!-- LOGO -->

            <div class="logo">
                <img src="/ressources/logo.png" alt="logo du restaurant vite et gourmand">
            </div>

<!-- NAVBAR DROITE -->

            <div>
                <a href="/code/php/contact.php" class="header-btn">Contact</a>
            </div>
            <div>
                <a href="/code/php/connexion.php" class="header-btn">Connexion</a>
            </div>

            <div class="user-icon" id="userBtn">
                <i class="fa-regular fa-circle-user"></i>
            </div>
        </div>

<!-- BURGER MENU -->

        <nav class="side-menu" id="sideMenu">
            <a href="/code/php/index.php"><i class="fa-regular fa-house"></i>Accueil</a>
            
            <a href="/code/php/menus.php"><i class="fa-solid fa-utensils"></i>Menus</a>
            <a href="/code/php/contact.php"><i class="fa-solid fa-message"></i>Contact</a>
            <a href="/code/php/connexion.php"><i class="fa-solid fa-user"></i>Connexion</a>
        </nav>

<!-- USER MENU -->

        <nav class="user-menu" id="userMenu">
            <a href="/code/php/commande.php"><i class="fa-solid fa-basket-shopping"></i>Ma commande</a>
            <a href="/code/php/commande.php"><i class="fa-solid fa-arrows-rotate"></i>Modifer ma commande</a>
            <a href="/code/php/Avis.php"><i class="fa-solid fa-comment"></i>laisser un avis</a>
            <a href="/code/php/informations.php"><i class="fa-solid fa-user-pen"></i>Modifier mes informations personnelles</a>
            <a href="/code/process/logout.php"><i class="fa-solid fa-power-off"></i>Déconnexion</a>
        </nav>
    
    </header>

<!-- OVERLAY -->

    <div class="overlay" id="overlay"></div>
    
    <script src="/code/js/header.js?v=<?php echo time(); ?>"></script>
</body>
</html>