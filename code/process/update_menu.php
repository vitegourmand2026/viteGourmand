<?php
session_start();



include '../php/config.php';

// UPLOAD IMG

function uploadImage($file, $folder) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowed = ['jpg', 'jpeg', 'png',];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return false;
    }
    
    $newname = uniqid() . '.' . $ext;
    $destination = '../../' . $folder . '/' . $newname;
    
    if (!is_dir('../../' . $folder)) {
        mkdir('../../' . $folder, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $folder . '/' . $newname;
    }
    
    return false;
}

// VERIF POST

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    $menu_id = intval($_POST['menu_id']);
    $theme = trim($_POST['theme']);
    $titre = trim($_POST['titre']);
    $regime = trim($_POST['regime']);
    $prix = floatval($_POST['prix']);
    $personne = intval($_POST['personne']);
    $description = trim($_POST['description']);
    $allergenes = isset($_POST['allergenes']) ? $_POST['allergenes'] : [];
    
    if ($menu_id > 0) {
        try {
            
            $pdo->beginTransaction();
            
// GESTION UPLOAD IMG

            $menu_image = null;
            if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $menu_image = uploadImage($_FILES['menu_image'], 'images/menus');
                if ($menu_image === false) {
                    throw new Exception("Erreur lors de l'upload de l'image du menu");
                }
            }
            
// UPDATE MENU

if ($menu_image) {

$stmt = $pdo->prepare("UPDATE menus 
                        SET theme = :theme, 
                        titre = :titre, 
                        regime = :regime, 
                        prix = :prix, 
                        personne = :personne, 
                        description = :description,
                        image = :image
                        WHERE menu_id = :menu_id");
$stmt->bindParam(':image', $menu_image, PDO::PARAM_STR);
} else {
$stmt = $pdo->prepare("UPDATE menus 
                        SET theme = :theme, 
                        titre = :titre, 
                        regime = :regime, 
                        prix = :prix, 
                        personne = :personne, 
                        description = :description 
                        WHERE menu_id = :menu_id");
}
            
$stmt->bindParam(':theme', $theme, PDO::PARAM_STR);
$stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
$stmt->bindParam(':regime', $regime, PDO::PARAM_STR);
$stmt->bindParam(':prix', $prix);
$stmt->bindParam(':personne', $personne, PDO::PARAM_INT);
$stmt->bindParam(':description', $description, PDO::PARAM_STR);
$stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
$stmt->execute();           
            
// GERER IMG PLATS

            if (isset($_FILES['plate_images'])) {
                foreach ($_FILES['plate_images']['tmp_name'] as $plate_id => $tmp_name) {
                    if ($_FILES['plate_images']['error'][$plate_id] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['plate_images']['name'][$plate_id],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['plate_images']['error'][$plate_id]
                        ];
                        
                        $plate_image = uploadImage($file, 'images/plates');
                        if ($plate_image !== false && $plate_image !== null) {
                            $stmt = $pdo->prepare("UPDATE plates SET image = :image WHERE plate_id = :plate_id");
                            $stmt->bindParam(':image', $plate_image, PDO::PARAM_STR);
                            $stmt->bindParam(':plate_id', $plate_id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
            }
                       
// DELETE RELATIONS ALLERGENES

            $stmt = $pdo->prepare("DELETE FROM menu_allergenes WHERE menu_id = :menu_id");
            $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
            $stmt->execute();
            
// INSERT NOUVELLES RELATION

            if (!empty($allergenes)) {
                $stmt = $pdo->prepare("INSERT INTO menu_allergenes (menu_id, allergenes_id) VALUES (:menu_id, :allergene_id)");
                foreach ($allergenes as $allergene_id) {
                    $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
                    $stmt->bindParam(':allergene_id', $allergene_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            
// COMMIT

            $pdo->commit();
            
// REDIRECTION DETAIL MENU

            header('Location: /code/php/admin/sections/detailMenuAdmin.php?id=' . $menu_id . '&success=updated');
            exit;
            
        } catch (Exception $e) {
            
            $pdo->rollBack();
            
            // Rediriger avec message d'erreur
            header('Location: /code/php/admin/sections/detailMenuAdmin.php?id=' . $menu_id . '&error=update_failed');
            exit;
        }
    }
}

// Si la requête n'est pas valide, rediriger
header('Location: /code/php/admin/sections/detailMenuAdmin.php');
exit;
?>