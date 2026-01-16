<?php
// FICHIER EXEMPLE


/**
 * INSTRUCTIONS D'INSTALLATION
 
 * 1. Copier ce fichier et le renommer en config_local.php ou en config_production.php selon le besoin
 * 2. Modifier les valeurs avec vos identifiants et mot de passe
 
 * DÉVELOPPEMENT LOCAL :
 
 * - host : localhost
 * - dbname : viteGourmand 
 * - username : root
 * - password : root 
 
 * PRODUCTION :
 
 * - $host = 'votre_hostname_mysql';              
 * - $dbname = 'votre_nom_base_production';       
 * - $username = 'votre_utilisateur_mysql';       
 * - $password = 'votre_mot_de_passe';    
 
 */

// Paramètres de connexion

$host = 'localhost';                   
$dbname = 'nom_de_votre_base';         
$username = 'votre_utilisateur';        
$password = 'votre_mot_de_passe';      

// Options PDO sécurité

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        
    PDO::ATTR_EMULATE_PREPARES   => false,                   
];

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);
} catch (PDOException $e) {
    // Erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>