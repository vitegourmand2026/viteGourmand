<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/service_mail.php';

// VERIF ROLE

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'employee'])) {
    header('Location: /code/php/connexion.php');
    exit;
}

// GET DONNÉES

$id = intval($_GET['id'] ?? 0);
$statut = $_GET['statut'] ?? '';
$motif_annulation = $_GET['motif_annulation'] ?? '';

$validStatus = ['acceptee','preparation','livraison','livree','retour','terminee','annulee'];

if ($id <= 0 || !in_array($statut, $validStatus)) {
    $_SESSION['error'] = "Données invalides";
    header('Location: /code/php/admin/sections/gestionCommandes.php'); 
    exit;
}

// SELECT MAIL POUR TERMINEE ET RETOUR

$emailClient = null;
if ($statut === 'retour' || $statut === 'terminee'){
    $queryEmail = "SELECT u.email, u.prenom, u.nom 
                   FROM commandes c 
                   INNER JOIN users u ON c.user_id = u.id 
                   WHERE c.id = :id";
    $stmtEmail = $pdo->prepare($queryEmail);
    $stmtEmail->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtEmail->execute();
    $clientInfo = $stmtEmail->fetch(PDO::FETCH_ASSOC);
    
    if ($clientInfo) {
        $emailClient = $clientInfo['email'];
        $prenomClient = $clientInfo['prenom'];
        $nomClient = $clientInfo['nom'];
    }
}

// UPDATE STATUT ANNULÉE

if ($statut === 'annulee' && !empty($motif_annulation)) {
    $query = "UPDATE commandes SET statut = :statut, motif_annulation = :motif_annulation WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':motif_annulation', $motif_annulation);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
} else {
    $query = "UPDATE commandes SET statut = :statut WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
}

$stmt->execute();

// ENVOI MAIL RETOUR

if ($statut === 'retour' && $emailClient) {
    $subject = "En attente du retour de matériel";
    $htmlContent = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #2d6a4f; color: white; padding: 20px; text-align: center; border-radius: 5px; }
            .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; border-radius: 5px; }
            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
            .highlight { background-color: #fff3cd; padding: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Vite et Gourmand</h2>
            </div>
            <div class='content'>
                <p>Bonjour " . htmlspecialchars($prenomClient) . " " . htmlspecialchars($nomClient) . ",</p>
                
                <p>Nous espérons que tout s'est bien passé.</p>
                
                <div class='highlight'>
                    <strong> Rappel important :</strong>
                    <p>Nous tenons à vous rappeler que le matériel prêté doit être restitué dans les <strong>10 jours ouvrés</strong> à compter de la date de la livraison de la prestation.</p>
                </div>
                
                <p>Sans quoi, vous devrez régler la somme de <strong>600 € TTC</strong> comme indiqué dans les conditions générales de vente disponibles sur notre site.</p>
                
                <p>Merci encore d'avoir choisi <strong>Vite et Gourmand</strong>.</p>
            </div>
            <div class='footer'>
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $emailSent = sendEmailBrevo($emailClient, $subject, $htmlContent);
    
    if ($emailSent) {
        $_SESSION['success'] = "Email retour marchandise envoyé au client";
    }
}
// ENVOI MAIL TERMINÉE

elseif ($statut === 'terminee' && $emailClient) {
    $subject = "Laissez nous un avis";
    $htmlContent = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #2d6a4f; color: white; padding: 20px; text-align: center; border-radius: 5px; }
            .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; border-radius: 5px; }
            .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #666; }
            .highlight { background-color: #d1f4e0; padding: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Vite et Gourmand</h2>
            </div>
            <div class='content'>
                <p>Bonjour " . htmlspecialchars($prenomClient) . " " . htmlspecialchars($nomClient) . ",</p>
                
                <p>Nous espérons que tout s'est bien passé.</p>
                
                <div class='highlight'>
                    <p>Nous tenons à vous remercier d'avoir choisi <strong>Vite et Gourmand</strong> pour votre prestation.</p>
                </div>
                
                <p>N'hésitez pas à nous laisser un avis grâce à l'onglet : <strong>Laisser un avis</strong> dans le menu utilisateur.</p>
                
                <p>Merci encore et à très bientôt !</p>
            </div>
            <div class='footer'>
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $emailSent = sendEmailBrevo($emailClient, $subject, $htmlContent);
    
    if ($emailSent) {
        $_SESSION['success'] = "Email demande avis envoyé au client";
    }
}



header('Location: /code/php/admin/sections/gestionCommandes.php');
exit;
?>