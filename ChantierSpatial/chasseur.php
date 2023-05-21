<?php
if (!isset($_SESSION)) {
    session_start();
}

$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idPlanete = $_SESSION['idPlanete'];
$idUnivers = $_SESSION['idUnivers'];
var_dump($idJoueur);
getChasseur($idJoueur, $idPlanete);




function checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)
{
    $idUnivers = $_SESSION['idUnivers'];
    // Récupérer les ressources actuelles du joueur
    $query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
    $ressources = $stmt->fetch(PDO::FETCH_ASSOC);

    $stockMetal = $ressources['stockMetal'];
    $stockEnergie = $ressources['stockEnergie'];
    $stockDeuterium = $ressources['stockDeuterium'];

    // Vérifier si le joueur possède suffisamment de ressources
    if ($stockMetal >= $coutMetal && $stockEnergie >= $coutEnergie && $stockDeuterium >= $coutDeuterium) {
        return true;
    } else {
        return false;
    }
}
function updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)
{
    $idUnivers = $_SESSION['idUnivers'];
    // Récupérer les ressources actuelles du joueur
    $query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
    $ressources = $stmt->fetch(PDO::FETCH_ASSOC);

    $stockMetal = $ressources['stockMetal'];
    $stockEnergie = $ressources['stockEnergie'];
    $stockDeuterium = $ressources['stockDeuterium'];

    // Calculer les ressources restantes
    $Metal = $stockMetal - $coutMetal;
    $Energie = $stockEnergie - $coutEnergie;
    $Deuterium = $stockDeuterium - $coutDeuterium;
    // var_dump($Metal, $Energie, $Deuterium);
    // Mise à jour des ressources du joueur;
    $query = "UPDATE ressource SET stockMetal = :restMetal, stockEnergie = :restEnergie, stockDeuterium = :restDeuterium WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':restMetal', $Metal);
    $stmt->bindValue(':restEnergie', $Energie);
    $stmt->bindValue(':restDeuterium', $Deuterium);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
}



function getChasseur($idJoueur, $idPlanete) {
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    
    $query = "SELECT niveauChantierSpatial FROM infrastructure WHERE idPlanete = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idPlanete', $idPlanete);
    $stmt->execute();
    $niveauChantier = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($niveauChantier);
    // var_dump($idPlanete);
    if ($niveauChantier['niveauChantierSpatial'] >= 1) {
        $query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'chasseur'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $cout = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($cout);
        // Récupérer les coûts
        $coutMetal = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
        $coutEnergie = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
        $coutDeuterium = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

        if (checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)) {
            // Si oui, on effectue la recherche pour le ions
            // Mise à jour du niveau de la technologie ions dans l'infrastructure
            $query = "UPDATE flotte SET nb_chasseur = nb_chasseur + 1 WHERE idPlanete = :idPlanete";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':idPlanete', $idPlanete);
            $stmt->execute();
            // Mise à jour des ressources du joueur après
            updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium);
            $_SESSION['good_alert'] = "Chasseur aquis.";
            header('Location: ./chantier.php');

    } else {
        $_SESSION['bad_alert'] = "Vous n'avez pas assez de ressources !";
        header('Location: ./chantier.php');
        exit();
    }
    } 
    else {
        $_SESSION['bad_alert'] = "Vous n'avez pas de chantier spatial sur cette planète !";
        header('Location: ./chantier.php');
        exit();
    }
}


?>