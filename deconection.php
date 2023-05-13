<?php
// Début de la session
session_start();

// Destruction de toutes les données de session
session_destroy();

// Redirection vers la page de connexion ou autre page appropriée
header('Location: acceuille\index.php');
exit();
?>
