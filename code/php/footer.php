<?php



require_once __DIR__ . '/config.php';  

// SELECT HORAIRES

$query = "SELECT texte FROM horaires LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$horaire = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/footer.css?v=<?php echo time(); ?>">
    
    <title>footer</title>
</head>
<body>
    

    <footer class="footer-container">
    <div class="footer-link">
        <a href="/code/php/cgv.php">CGV</a>
    </div>

    <div class="footer-time">
        <?= $horaire ? nl2br(htmlspecialchars($horaire['texte'])) : 'Aucun horaire disponible.'; ?>
    </div>

    <div class="footer-link">
        <a href="/code/php/mentions.php">MENTIONS LÉGALES</a>
    </div>
</footer>

</body>
</html>