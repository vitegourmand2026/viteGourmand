<?php

require_once './../../config.php';

// SELECT EMPLOYÉS

try {
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email ,telephone, adresse FROM users WHERE role = 'employee' ORDER BY prenom, nom, email, telephone , adresse");
    $stmt->execute();
    $employees = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des employés : " . $e->getMessage());
}

// SUPPRESSION EMPLOYÉ

if (isset($_POST['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'employee'");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}?>




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
    <link rel="icon" type="image/png" href="/ressources/icons/toque.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/code/css/gestion-employés.css?v=<?php echo time(); ?>">
    <title>gestion employés</title>
</head>

<body>
    
<?php include"../admin_header.php"?>;

   <div class="btn-container">
        <button class="main-btn">gestion des employés</button>
    </div>
 <div class="employee-list">

 <!-- LISTE EMPLOYÉS-->
        
            <?php foreach ($employees as $employee): ?>
                <div class="employee-item">
                    <span class="employee-name">
                        <?php echo htmlspecialchars($employee['prenom'] . ' ' . $employee['nom']); ?>
                        </span>
                    
                    <?php echo htmlspecialchars($employee['email']); ?><br>
                    <?php echo htmlspecialchars($employee['telephone']); ?><br>
                    <?php echo htmlspecialchars($employee['adresse']); ?><br>
                    

                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                        <input type="hidden" name="delete_id" value="<?php echo $employee['id']; ?>">
                        <button type="submit" class="btn-delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        
    </div>


  <div class="add-container">
    <a href="ajoutEmploye.php" class="btn-add">AJOUTER</a>
</div>
        
</body>

</html>