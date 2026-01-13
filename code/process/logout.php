<?php
session_start();

// DETRUIRE SESSION

$_SESSION = array();

session_destroy();

// REDIRECTION CONNEXION

header('Location: /code/php/connexion.php');
exit();
?>