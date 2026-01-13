<?php

require_once __DIR__ . '/../../config.php';?>


<?php
session_start();

if ($_SESSION['role'] === 'admin') {
    include '../admin_header.php';
} elseif ($_SESSION['role'] === 'employee') {
    include '../../employee/employe_header.php';
}
?>
<?php

// SELECT MESSAGES

$sql = "SELECT * FROM contact_messages";

$stmt = $pdo->query($sql);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <button class="main-btn">Messages</button>
</div>

<!-- TABLEAU DES MESSAGES-->

<div class="tableContainer">
    <table>
        <caption>Tableau des messages</caption>
        <tbody>
            <?php foreach ($messages as $m): ?>
            <tr>
                <th><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></th>
                <td><?= nl2br(htmlspecialchars($m['email'])) ?></td>
                <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                
                <td>
                    <form action="../../../process/supprimer_messages.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce message ?');">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
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
