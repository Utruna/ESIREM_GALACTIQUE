<?php
if (!isset($_SESSION)) {
    session_start();
}

// Destruction de toutes les données de session
session_destroy();

// Redirection vers la page de connexion ou autre page appropriée
header('Location: acceuille\index.php');
exit();
?>
