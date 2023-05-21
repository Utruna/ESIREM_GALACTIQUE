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

    $_SESSION['good_alert'] = "Planete aquise !";

    // Vérification de l'existence d'une table infrastructure pour la planete
    $stmt = $pdo->prepare('SELECT * FROM infrastructure WHERE idPlanete = :idPlanete');
    $stmt->execute(['idPlanete' => $idPlanete]);
    $infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si la table existe on la supprime
    if ($infrastructure) {
        $stmt = $pdo->prepare('DELETE FROM infrastructure WHERE idPlanete = :idPlanete');
        $stmt->execute(['idPlanete' => $idPlanete]);
    }
    // On crée une nouvelle table infrastructure pour la planete
    $stmt = $pdo->prepare('INSERT INTO infrastructure (idPlanete, niveauLabo, niveauChantierSpatial, niveauUsineNanite, niveauUsineMetal, niveauCentraleSolaire, niveauCentraleFusion, niveauArtillerieLaser, niveauCannonIons, niveauBouclier, niveauTechEnergie, niveauTechLaser, niveauTechIons, niveauTechBouclier, niveauTechArmement) VALUES (:idPlanete, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0)');
    $stmt->execute(['idPlanete' => $idPlanete]);
    // Renvoyer vers la page de selection de galaxie
    header('Location: ./galaxie.php');
}
