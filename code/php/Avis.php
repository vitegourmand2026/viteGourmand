

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/connexion.css?v=<?php echo time(); ?>">
    <title>dépot avis</title>
</head>

<body>
   <?php include 'header.php';?>


    <div>
        <img src="/ressources/ban.png" alt="image de plat du restaurant">

    </div>


    <section class="form-container">

    <!-- POST VERS PROCESS-->

    <form class="contact-form" method="POST" action="../process/traitement_avis.php">


       

            <div class="container">
                <h2>LAISSER UN AVIS</h2>
            </div>

            <div class="prevention">
            <p> Dans le cadre de notre politique de respect, 
                nous vous demandons de rester courtois lors de la redaction de votre avis.
                Ceci afin de garantir le respect envers nos employés et nos utilisateurs.
                Tout avis ne respectant pas ces conditions sera supprimé.
                Merci.
            </p>
            </div>


            <div>
                <label for="message">Votre message </label>
                <textarea id="message" name="message" placeholder="Votre message"></textarea>
            </div>
            
            <div>

            <button type="submit"class="submit-btn">DÉPOSER</button>
            </div>

        </form>
    </section>

</body>

</html>