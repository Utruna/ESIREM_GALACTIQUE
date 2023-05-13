<?php
if (!isset($_SESSION)) {
    session_start();
}
// Inclure le fichier des fonctions
include 'functions.php';

// Vérifier si l'ID de la planète a été envoyé
if (isset($_POST['idPlanete'])) {
    // Récupérer l'ID de la planète depuis la requête AJAX
    $idPlanete = $_POST['idPlanete'];

    // Appeler la fonction acquerirPlanete avec l'ID de la planète
    $stmt = $pdo->prepare('UPDATE planete SET idJoueur = :idJoueur WHERE id = :idPlanete');
    $stmt->execute(['idJoueur' => $_SESSION['idJoueur'], 'idPlanete' => $idPlanete]);

    // Envoyer une réponse au client (facultatif)
    echo 'Planète acquise avec succès !';

    // Renvoyer vers la page de selection de galaxie
    header('Location: ./galaxie.php');
}



?>