<?php
session_start();
require_once __DIR__ . '/../php/config.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texte = trim($_POST['horaires']);
    
    if (empty($texte)) {
        $_SESSION['error'] = "Le champ horaires ne peut pas être vide.";
          if ($_SESSION['role'] === 'admin') {
            header('Location: /code/php/admin/dashboard.php');
        } elseif ($_SESSION['role'] === 'employee') {
            header('Location: /code/php/employee/dashboard.php');
}
        exit();
    }
    
    
   try {

// UPDATE HORAIRES

        $stmt = $pdo->prepare("UPDATE horaires SET texte = ? WHERE id = 1");
        $stmt->execute([$texte]);

        $_SESSION['success'] = "Horaires mis à jour avec succès !";

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
       
    }

// REDIRECTION DASAHBOARD ADMIN + EMPLOYÉ

    if ($_SESSION['role'] === 'admin') {
    header('Location: /code/php/admin/dashboard.php');
} elseif ($_SESSION['role'] === 'employee') {
    header('Location: /code/php/employee/dashboard.php');
}

?>

