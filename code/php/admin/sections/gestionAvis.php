<?php

require_once __DIR__ . '/../../config.php'; ?>

<?php
session_start();
if ($_SESSION['role'] === 'admin') {
    include '../admin_header.php';
} elseif ($_SESSION['role'] === 'employee') {
    include '../../employee/employe_header.php';
}
?>

<?php

// SELECT AVIS 

$sql = "
    SELECT avis.id, avis.commentaire, avis.statut, users.nom, users.prenom
    FROM avis
    JOIN users ON avis.user_id = users.id
    ORDER BY avis.id DESC
";

$stmt = $pdo->query($sql);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avis</title>
    <link rel="stylesheet" href="/code/css/gestionAvis.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="btn-container">
    <button class="main-btn">Gestion des avis</button>
</div>

<div class="tableContainer">
    <table>
        <caption>Tableau des avis utilisateurs</caption>
        <tbody>
            <?php foreach ($avis as $a): ?>
            <tr>
                <th><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></th>
                <td><?= nl2br(htmlspecialchars($a['commentaire'])) ?></td>
                <td>
                    <?php if($a['statut'] != 'validé'): ?>
                        <form action="../../../process/valider_avis.php" method="POST">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <button type="submit" class="btn-validate">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        Validé
                    <?php endif; ?>
                </td>
                <td>
                    <form action="../../../process/supprimer_avis.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet avis ?');">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
