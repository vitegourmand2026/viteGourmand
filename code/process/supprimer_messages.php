<?php

require_once __DIR__ . '/../php/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id']; 

// DELETE MESSAGE

    $sql = "DELETE FROM contact_messages WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

// REDIRECTION GESTION MESSAGE

header('Location: ../php/admin/sections/messages.php');
exit;
