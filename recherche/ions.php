<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
$idPlanete = $_POST['idPlanete'];

//upgradeEnergie($idJoueur, $idPlanete);
upgradeIons($idJoueur, $idPlanete);

function countResearch($query, $idJoueur)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['count'];
}

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
    $restMetal = $stockMetal - $coutMetal;
    $restEnergie = $stockEnergie - $coutEnergie;
    $restDeuterium = $stockDeuterium - $coutDeuterium;
    var_dump($restMetal, $restEnergie, $coutDeuterium);
    // Mise à jour des ressources du joueur
    $query = "UPDATE ressource SET stockMetal = :restMetal, stockEnergie = :restEnergie, stockDeuterium = :restDeuterium WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':restMetal', $restMetal);
    $stmt->bindValue(':restEnergie', $restEnergie);
    $stmt->bindValue(':restDeuterium', $restDeuterium);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
}



function upgradeIons($idJoueur, $idPlanete)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    echo "Début de la vérification";
    $query = "SELECT niveauLabo, niveauTechIons FROM infrastructure WHERE idPlanete = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idPlanete', $idPlanete);
    $stmt->execute();
    $infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);


    // Récupérer les niveaux
    $niveauLabo = $infrastructure['niveauLabo'];
    $niveauTechIons = $infrastructure['niveauTechIons'];

    // Si le niveau max est atteint on n'améliore pas
    if ($niveauTechIons >= 10) {
        $_SESSION['bad_alert'] = "Vous avez atteint le niveau maximum pour la technologie ions.";
        header('Location: ./recherche.php');
        exit();
    }
    // Verifier si le niveau de recherche energetique est suffisant
    $query = "SELECT niveauTechLaser FROM infrastructure
                WHERE idPlanete = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idPlanete', $idPlanete);
    $stmt->execute();
    $verif = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($verif['niveauTechLaser'] >= 5) {
        $query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_ions'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $cout = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les coûts
        $coutMetal = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
        $coutEnergie = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
        $coutDeuterium = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;
        var_dump($cout['coutDeuterium']);
        // Vérifier les ressources disponibles
        if (checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)) {
            // Si oui, on effectue la recherche pour le ions
            // Mise à jour du niveau de la technologie ions dans l'infrastructure
            $query = "UPDATE infrastructure SET niveauTechIons = niveauTechIons + 1 WHERE idPlanete = :idPlanete";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':idPlanete', $idPlanete);
            $stmt->execute();

            // Mise à jour des ressources du joueur après
            updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium);
            $_SESSION['good_alert'] = "La recherche pour le ions a bien été effectuée.";
            header('Location: ./recherche.php');
        } else {
            $_SESSION['bad_alert'] = "Le joueur ne possède pas les ressources nécessaires pour effectuer la recherche.";
            header('Location: ./recherche.php');
        }
    } else {
        $_SESSION['bad_alert'] = "Le joueur ne possède pas toutes les recherches nécessaires pour obtenir le laser.";
        header('Location: ./recherche.php');
    }
}
