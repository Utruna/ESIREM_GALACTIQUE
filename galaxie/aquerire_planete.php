<?php
if (!isset($_SESSION)) {
    session_start();
}
// Inclure le fichier des fonctions
include 'functions.php';
// Vérifier si l'ID de la planète a été envoyé

if (isset($_POST['idPlanete'])) {
    $idPlanete = $_POST['idPlanete'];
    $idPlanete = !empty($idPlanete) ? $idPlanete : 0;
} else {
    $_SESSION['bad_alert'] = "Erreur lors de l'aquisition de la planete !";
    header('Location: ./galaxie.php');
}

if (isset($idPlanete)) {
    // Récupérer l'ID de la planète depuis la requête AJAX

    echo $idPlanete;

    // On crée une nouvelle table infrastructure pour la planete
    $stmt = $pdo->prepare('INSERT INTO infrastructure 
    (
        idPlanete, 
        niveauLabo, 
        niveauChantierSpatial, 
        niveauUsineNanite, 
        niveauUsineMetal, 
        niveauCentraleSolaire, 
        niveauCentraleFusion, 
        niveauArtillerieLaser, 
        niveauCannonIons, 
        niveauBouclier, 
        niveauTechEnergie, 
        niveauTechLaser, 
        niveauTechIons, 
        niveauTechBouclier, 
        niveauTechArmement
    ) 
    VALUES (:idPlanete, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0)');
    $stmt->execute(['idPlanete' => $idPlanete]);


    // on crée une table flotte pour la planete
    $query = "INSERT INTO flotte (idPlanete, idStatFlotte, nb_chasseur, nb_croiseur, nb_transporteur, nb_coloniseur) 
          VALUES (:idPlanete, :idStatFlotte, 0, 0, 0, 0)";
    $stmt = $pdo->prepare($query);
    // La flotte étant vide on lui attribue le statue 1 (attend)
    $idStatFlotte = 1;
    $stmt->bindParam(':idPlanete', $idPlanete);
    $stmt->bindParam(':idStatFlotte', $idStatFlotte);
    $stmt->execute();

    $stmt = $pdo->prepare('SELECT * FROM planete WHERE id = :idPlanete');
    $stmt->execute(['idPlanete' => $idPlanete]);
    $planete = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "idJoueur : " . $planete['idJoueur'];


    // Modifier l'idJoueur de la planete pour l'aquérire
    $stmt = $pdo->prepare('UPDATE planete SET idJoueur = :idJoueur WHERE id = :idPlanete');
    $stmt->execute(['idJoueur' => $_SESSION['idJoueur'], 'idPlanete' => $idPlanete]);
    echo "idJoueur mis a jour !";

    // vérification modification idJoueur
    $stmt = $pdo->prepare('SELECT * FROM planete WHERE id = :idPlanete');
    $stmt->execute(['idPlanete' => $idPlanete]);
    $planete = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "idJoueur : " . $planete['idJoueur'];


    // Renvoyer vers la page de selection de galaxie
    $_SESSION['good_alert'] = "Planete aquise !";
    header('Location: ./galaxie.php');
}
