<?php
session_start();

include '../php/config.php';

// VERIF POST

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    $menu_id = intval($_POST['menu_id']);
    
    if ($menu_id > 0) {
        try {
            
            $pdo->beginTransaction();
            
// DELETE RELATION ALLERGENES

            $stmt = $pdo->prepare("DELETE FROM menu_allergenes WHERE menu_id = :menu_id");
            $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
            $stmt->execute();
            
// DELETE PLATS

            $stmt = $pdo->prepare("DELETE FROM plates WHERE menu_id = :menu_id");
            $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
            $stmt->execute();
            
// DELETE MENU

            $stmt = $pdo->prepare("DELETE FROM menus WHERE menu_id = :menu_id");
            $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
            $stmt->execute();
            
// COMMIT
            $pdo->commit();
            
// REDIRECTION GESTION MENUS
            header('Location: /code/php/admin/sections/gestionMenus.php?success=deleted');
            exit;
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            
            // Rediriger avec message d'erreur
            header('Location: /code/php/detailMenuAdmin.php?id=' . $menu_id . '&error=delete_failed');
            exit;
        }
    }
}

// Si la requête n'est pas valide, rediriger
header('Location: /code/php/menus.php');
exit;
?>