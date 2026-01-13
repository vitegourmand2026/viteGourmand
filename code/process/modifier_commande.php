<?php
session_start();
require_once '../php/config.php';

// VERIF USER 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/connexion.php');
    exit;
}

// VERIF POST

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../php/menus.php');
    exit;
}

$commande_id = (int) ($_POST['commande_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($commande_id <= 0 || !in_array($action, ['modifier', 'annuler'], true)) {
    $_SESSION['error'] = "Action invalide.";
    header('Location: ../php/menus.php');
    exit;
}

// SELECT COMMANDE

$stmt = $pdo->prepare("
    SELECT id
    FROM commandes
    WHERE id = :id
      AND user_id = :user_id
      AND statut = 'en_attente'
");
$stmt->execute([
    ':id' => $commande_id,
    ':user_id' => $_SESSION['user_id']
]);

$commande = $stmt->fetch();

if (!$commande) {
    $_SESSION['error'] = "Commande non modifiable.";
    header('Location: ../php/menus.php');
    exit;
}

/* MODIFIER DATE + HEURE */

if ($action === 'modifier') {

    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';

    if (empty($date) || empty($heure)) {
        $_SESSION['error'] = "Date et heure obligatoires.";
        header("Location: ../php/commande.php?id=$commande_id");
        exit;
    }

    // DATE +6 JOURS

    $date_min = new DateTime('+6 days');
    if (new DateTime($date) < $date_min) {
        $_SESSION['error'] = "La livraison doit être prévue au minimum 6 jours à l’avance.";
        header("Location: ../php/commande.php?id=$commande_id");
        exit;
    }

    // HORAIRES

    if ($heure < '09:00' || $heure > '20:00') {
        $_SESSION['error'] = "Heure de livraison invalide.";
        header("Location: ../php/commande.php?id=$commande_id");
        exit;
    }

    // UPDATE

    $stmt = $pdo->prepare("
        UPDATE commandes
        SET date_livraison = :date,
            heure_livraison = :heure
        WHERE id = :id
          AND user_id = :user_id
          AND statut = 'en_attente'
    ");

    $stmt->execute([
        ':date' => $date,
        ':heure' => $heure,
        ':id' => $commande_id,
        ':user_id' => $_SESSION['user_id']
    ]);

    $_SESSION['success'] = "Votre commande a été mise a jour.";
    header("Location: ../php/commande.php?id=$commande_id");
    exit;
}

/* ANNULER COMMANDE */

if ($action === 'annuler') {

    $stmt = $pdo->prepare("
        UPDATE commandes
        SET statut = 'annulee'
        WHERE id = :id
          AND user_id = :user_id
          AND statut = 'en_attente'
    ");

    $stmt->execute([
        ':id' => $commande_id,
        ':user_id' => $_SESSION['user_id']
    ]);

    $_SESSION['success'] = "Votre commande a été annulée.";
    header('Location: ../php/menus.php');
    exit;
}
