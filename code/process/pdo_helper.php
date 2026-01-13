<?php
// pdo_helper.php - Fonctions utilitaires pour PDO

require_once __DIR__ . '/../php/config.php';

/**
 * Récupérer un utilisateur par email
 */
function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

/**
 * Récupérer un utilisateur par ID
 */
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Vérifier si un email existe déjà
 */
function emailExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Créer un nouvel utilisateur
 */
function createUser($nom, $prenom, $email, $password, $role = 'user') {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, mot_de_passe, role, date_creation) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    return $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role]);
}

/**
 * Mettre à jour le dernier login
 */
function updateLastLogin($userId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET derniere_connexion = NOW() WHERE id = ?");
    return $stmt->execute([$userId]);
}

/**
 * Récupérer tous les utilisateurs (pour admin)
 */
function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, date_creation FROM users ORDER BY date_creation DESC");
    return $stmt->fetchAll();
}

/**
 * Supprimer un utilisateur
 */
function deleteUser($userId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$userId]);
}

/**
 * Changer le rôle d'un utilisateur
 */
function updateUserRole($userId, $newRole) {
    global $pdo;
    $validRoles = ['user', 'employee', 'admin'];
    
    if (!in_array($newRole, $validRoles)) {
        return false;
    }
    
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    return $stmt->execute([$newRole, $userId]);
}
?>
