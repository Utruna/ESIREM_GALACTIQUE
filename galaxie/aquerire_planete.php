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


    // on crée une table flotte pour la planete
    $query = "INSERT INTO flotte (idPlanete, idStatFlotte, nb_chasseur, nb_croiseur, nb_transporteur, nb_coloniseur) 
          VALUES (:idPlanete, :idStatFlotte, 0, 0, 0, 0)";
    $stmt = $pdo->prepare($query);
    // La flotte étant vide on lui attribue le statue 1 (attend)
    $idStatFlotte = 1;
    $stmt->bindParam(':idPlanete', $idPlanete, PDO::PARAM_INT);
    $stmt->bindParam(':idStatFlotte', $idStatFlotte, PDO::PARAM_INT);

    // Renvoyer vers la page de selection de galaxie
    $_SESSION['good_alert'] = "Planete aquise !";
    header('Location: ./galaxie.php');
}
