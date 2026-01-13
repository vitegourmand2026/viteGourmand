<?php
session_start();
require_once __DIR__ . '/../php/config.php';

// VÉRIFICATION POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
// VALIDATION

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: ../php/connexion.php');
        exit();
    }
    
    try {

 // SELECT INFOS USER

        $stmt = $pdo->prepare("SELECT id, nom, prenom, email, mot_de_passe, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
// CHECK USER ET PASSWORD

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            
// STOCKER LES INFOS

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            
// MESSAGE SUCCÈS

            $_SESSION['success'] = "Connexion réussie ! Bienvenue " . htmlspecialchars($user['prenom']) . " !";
            
// REDIRECTION SELON LE RÔLE

            if ($user['role'] === 'admin') {
                header('Location: ../php/admin/dashboard.php');
            } elseif ($user['role'] === 'employee') {
                header('Location: ../php/employee/dashboard.php');
            } else {

// REDIRECTION USER

                if (isset($_SESSION['commande_en_cours'])) {
                    header('Location: ../php/panier.php');
                } else {
                    header('Location: ../php/index.php');
                }
            }
            exit();
            
        } else {
//  ÉCHEC 
            $_SESSION['error'] = "Email ou mot de passe incorrect";
            header('Location: ../php/connexion.php');
            exit();
        }
        
    } catch (PDOException $e) {
// ERREUR BASE DE DONNÉES
        $_SESSION['error'] = "Erreur de connexion. Veuillez réessayer.";
        error_log("Erreur login: " . $e->getMessage());
        header('Location: ../php/connexion.php');
        exit();
    }
    
} else {
    // ACCÈS DIRECT NON AUTORISÉ
    header('Location: ../php/connexion.php');
    exit();
}
?>