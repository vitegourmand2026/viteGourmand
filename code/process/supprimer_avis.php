<?php

require_once __DIR__ . '/../php/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id']; 

// DELETE AVIS

    $sql = "DELETE FROM avis WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

// REDIRECTION GESTION AVIS

header('Location: ../php/admin/sections/gestionAvis.php');
exit;
