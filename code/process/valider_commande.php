<?php
session_start();
require_once '../php/config.php';

// VERIF POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /code/php/menus.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// RECUP INFOS PANIER DEPUIS LE POST 

$menu_id = isset($_POST['menu_id']) ? (int)$_POST['menu_id'] : 0;
$nb_personnes = isset($_POST['nb_personnes']) ? (int)$_POST['nb_personnes'] : 0;
$sous_total = isset($_POST['sous_total']) ? (float)$_POST['sous_total'] : 0;
$frais_livraison = isset($_POST['frais_livraison']) ? (float)$_POST['frais_livraison'] : 5.00;
$total = isset($_POST['total']) ? (float)$_POST['total'] : 0;

// RECUP INFOS LIVRAISON

$adresse_livraison = $_POST['adresse_livraison'] ?? '';
$code_postal = trim($_POST['code_postal'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$date_livraison = $_POST['date'] ?? '';
$heure_livraison = $_POST['heure'] ?? '';

// VERIF INFOS

if (!$menu_id || $sous_total <= 0 || $nb_personnes <= 0 || empty($adresse_livraison) || empty($date_livraison) || empty($heure_livraison) || empty($code_postal) || empty($ville)) {
    $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis et valides.";
    header('Location: /code/php/panier.php');
    exit;
}

try {

// INSERT COMMANDE

    $query = "INSERT INTO commandes 
              (user_id, menu_id, nb_personnes, sous_total, frais_livraison, total, adresse_livraison, code_postal, ville, date_livraison, heure_livraison, date_commande)
              VALUES 
              (:user_id, :menu_id, :nb_personnes, :sous_total, :frais_livraison, :total, :adresse_livraison, :code_postal, :ville, :date_livraison, :heure_livraison, NOW())";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
    $stmt->bindParam(':nb_personnes', $nb_personnes, PDO::PARAM_INT);
    $stmt->bindParam(':sous_total', $sous_total);
    $stmt->bindParam(':frais_livraison', $frais_livraison);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':adresse_livraison', $adresse_livraison);
    $stmt->bindParam(':code_postal', $code_postal);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':date_livraison', $date_livraison);
    $stmt->bindParam(':heure_livraison', $heure_livraison);
    
    if ($stmt->execute()) {
        // Nettoyer la session
        unset($_SESSION['commande_en_cours']); 
        $_SESSION['success'] = "Votre commande a été enregistrée avec succès !";
        header('Location: /code/php/index.php');
        exit;
    } else {
        throw new Exception("Échec de l'exécution de la requête");
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Une erreur est survenue : " . $e->getMessage();
    header('Location: /code/php/panier.php');
    exit;
}
?>