<?php
session_start();
require_once __DIR__ . '/../../config.php';

// VERIF ROLE
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /code/php/connexion.php');
    exit;
}

include '../admin_header.php';

// SELECT STATS PAR MENU

$query = "
    SELECT 
        m.titre AS menu_nom,
        COUNT(c.id) AS nb_commandes,
        CASE 
            WHEN COUNT(c.id) = 0 THEN 0
            ELSE SUM(c.total)
        END AS chiffre_affaires
    FROM menus m
    LEFT JOIN commandes c ON m.menu_id = c.menu_id
    GROUP BY m.menu_id, m.titre
    ORDER BY nb_commandes DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$statistiques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CALCUL TOTAL

$total_commandes = 0;
$total_ca = 0;
foreach ($statistiques as $stat) {
    $total_commandes += intval($stat['nb_commandes']);
    $total_ca += intval($stat['chiffre_affaires']);
}

// MAX GRAPHIQUE

$max_commandes = 0;
foreach ($statistiques as $stat) {
    if ($stat['nb_commandes'] > $max_commandes) {
        $max_commandes = $stat['nb_commandes'];
    }
}
// Éviter la division par zéro
if ($max_commandes == 0) {
    $max_commandes = 1;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Italiana&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="/code/css/statistiques.css?v=<?php echo time(); ?>">
    <title>Statistiques</title>
</head>

<body>
    <div class="btn-container">
        <button class="main-btn">STATISTIQUES</button>
    </div>

    <div class="container">

<!-- TABLEAU STATS -->

        <div class="table-section">
            <table>
                <caption class="table-caption">TABLEAU DES COMMANDES</caption>
                <thead>
                    <tr>
                        <th scope="col">Menus</th>
                        <th scope="col">Nb de commandes</th>
                        <th scope="col">CA (€)</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($statistiques as $stat): ?>
                            <tr>
                                <th><?php echo htmlspecialchars($stat['menu_nom']); ?></th>
                                <td class="nbCommande"><?php echo intval($stat['nb_commandes']); ?></td>
                                <td class="total"><?php echo number_format(floatval($stat['chiffre_affaires']), 2, ',', ' '); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        
<!-- TOTAL -->

                        <tr class="total">
                            <th>TOTAL</th>
                            <td><?php echo $total_commandes; ?></td>
                            <td><?php echo number_format($total_ca, 2, ',', ' '); ?> </td>
                        </tr>
                </tbody>
            </table>
        </div>

<!-- GRAPHIQUE -->

        <div class="chart-section">
            <div class="chart-title">GRAPHIQUE DES COMMANDES</div>
            
           
                
            <?php ?>
                <div class="bar-chart">
                    <?php foreach ($statistiques as $stat): ?>
                        <?php 
                            
                            $pourcentage = ($stat['nb_commandes'] / $max_commandes) * 100;
                        ?>
                        <div class="bar-item">
                            <div class="bar-label" title="<?php echo htmlspecialchars($stat['menu_nom']); ?>">
                                <?php echo strtoupper(htmlspecialchars($stat['menu_nom'])); ?>
                            </div>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: <?php echo $pourcentage; ?>%">
                                    <span class="bar-value"><?php echo $stat['nb_commandes']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
           
        </div>
    </div>

    

</body>
</html>