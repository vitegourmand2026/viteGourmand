<?php

session_start();
require_once __DIR__ . '/pdo_helper.php';

// VERIF POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
// VALIDATION

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($adresse) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: ../php/inscription.php');
        exit();
    }
    
// VALIDATION MAIL
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email invalide";
        header('Location: ../php/inscription.php');
        exit();
    }
    
// VERIF PASSWORD

    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas";
        header('Location: ../php/inscription.php');
        exit();
    }
    
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères";
        header('Location: ../php/inscription.php');
        exit();
    }
    
    try {

// VERIF MAIL EXISTE

        if (emailExists($email)) {
            $_SESSION['error'] = "Cet email est déjà utilisé";
            header('Location: ../php/inscription.php');
            exit();
        }
        
// HASH PASSWORD

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
// INSERT INFO USER

        $stmt = $pdo->prepare("
            INSERT INTO users (nom, prenom, email, telephone, adresse, mot_de_passe, role, date_creation) 
            VALUES (?, ?, ?, ?, ?, ?, 'user', NOW())
        ");
        
        if ($stmt->execute([$nom, $prenom, $email, $telephone, $adresse, $hashedPassword])) {
            $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header('Location: ../php/connexion.php');
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'inscription. Veuillez réessayer.";
            header('Location: ../php/inscription.php');
            exit();
        }
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données : " . $e->getMessage();
        error_log("Erreur inscription: " . $e->getMessage());
        header('Location: ../php/inscription.php');
        exit();
    }
    
} else {
    
    header('Location: ../php/inscription.php');
    exit();
}
?>