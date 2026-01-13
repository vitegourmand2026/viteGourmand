<?php
session_start();


require_once __DIR__ . '/config.php'; 

$query = "SELECT * FROM avis WHERE statut = 'validé' LIMIT 4";
$stmt = $pdo->prepare($query);
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
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

    <link rel="stylesheet" href="/code/css/index.css?v=<?php echo time(); ?>">
    


    <title>Accueil</title>

</head>

<body>
<?php include 'header.php';?>

 <?php 

// MESSAGE SUCCES
 
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


    
    <div class="img-test"></div>
    <div class="image-container">
        <img src="/ressources/ban.png" alt="image de plat du restaurant">
        <div class="text-main">Vite et Gourmand</div>
    </div>

    <section>

<!-- MAIN TEXT -->
        
        <div class="text-container">
            <div class=text-content>
                <p>Fondé en 2008 par trois amis traiteurs passionnés, notre établissement vous propose une cuisine de
                    qualité.</p>
                <p>Nous avons à coeur de favoriser le travail de produits locaux, frais, </p>
                <p>et issus de circuits courts principalement Français. En collaborant étroitement
                    avec les producteurs de la région de Bordeaux.</p>
                <p>Nos plats sont non seulement délicieux mais aussi respectueux de l’environnement et de l’économie
                    locale. En privilégiant les produits de saison, nous vous offrons des saveurs authentiques qui
                    reflètent les
                    richesses de notre région.</p>
                <p>Cette démarche éthique nous permet de soutenir les agriculteurs et artisans locaux tout en vous
                    proposant
                    des menus gourmands, créatifs, et diversifiés.</p>

            </div>
        </div>

    </section>

<!-- IMG+TEXT -->

    <div class="card-container">

        <div class="card">

            <img src="/ressources/restaurant.png" alt="image du restaurant">

            <div class="card-text">
                Nous vous proposons des menus
                gourmands, des menus événements
                et des menus du bout du monde.
            </div>
        </div>

        <div class="card">

            <img src="/ressources/equipe.png" alt="image des trois membres de l'equipe">

            <div class="card-text">
                Notre équipe et son chef <span class="name">Jean Parmentier</span>
                vous propose de nouvelles cartes au fil des saisons.
            </div>
        </div>

        <div class="card">
    <img src="/ressources/avis.png" alt="image d'un plat">
    
    <div class="card-text-avis">
        
            <?php foreach ($avis as $avisItem): ?>
                <?php echo htmlspecialchars($avisItem['commentaire']); ?>
                <br><br>
            <?php endforeach; ?>
       
    </div>
</div>
        </div>

    </div>

<?php include 'footer.php';?>


</body>

</html>