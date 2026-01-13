<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// VERIFER SESSION USER

if (!isset($_SESSION['user_id'])) {
    die("Erreur : vous devez être connecté pour laisser un avis.");
}

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message']);

// VERIF SI MESSAGE

if (empty($message)) {
    die("Veuillez écrire un message.");
}

try {

// INSERT AVIS

    $stmt = $pdo->prepare("
        INSERT INTO avis (user_id, commentaire)
        VALUES (:user_id, :commentaire)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':commentaire' => $message
    ]);
    
// MESSAGE SUCCÉS

    $_SESSION['success'] = "Votre avis a bien été envoyé";

// REDIRECTION ACCUEIL

    header("Location: ../php/index.php");
    exit();

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
