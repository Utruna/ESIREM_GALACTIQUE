<?php
if (!isset($_SESSION)) {
    session_start();
}
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
prod($pdo, $idJoueur, $idUnivers);

function prod($pdo, $idJoueur, $idUnivers)
{
    $deltaTemps = calculeTemps($pdo, $idJoueur);
    echo "calcule temps : " . $deltaTemps . "<br>";
    if ($deltaTemps > 60) {
        // nombre de minute depuis la dernière vérification
        $nbMinute = floor($deltaTemps / 60);
        echo "nombre de minute : " . $nbMinute . "<br>";
        $niveauInfra = recuperationLvlInfra($pdo, $idUnivers, $idJoueur);
        var_dump($niveauInfra);
        $prod = calculProd($pdo, $idJoueur, $idUnivers, $nbMinute, $niveauInfra);
        var_dump($prod);
        $metal = $prod['metal'];
        $energie = $prod['energie'];
        $deuterium = $prod['deuterium'];

        AjoutRessources($pdo, $idJoueur, $metal, $energie, $deuterium);
        echo "ajout ressource fait";
        miseAJourTemps($pdo, $idJoueur);
        echo "mise a jour temps fait";
        $_SESSION['good_alert'] = "Vos ressources ont été produites, gain : " . $metal . " de métal, " . $energie . " d'énergie et " . $deuterium . " de deutérium";
        header('Location: ../galaxie/manager.php');
    } else {
        $attente = 60 - $deltaTemps;
        $_SESSION['bad_alert'] = "attendez 1 minute que des ressources soient produites vous devez encore attendre " . $attente . " secondes";
        header('Location: ../galaxie/manager.php');
    }
}
function recuperationLvlInfra($pdo, $idUnivers, $idJoueur)
{
    $query = "SELECT i.niveauUsineMetal, i.niveauSynthetiseurDeut, i.niveauCentraleSolaire, i.niveauCentraleFusion
              FROM planete p
              INNER JOIN systeme_solaire ss ON p.idSystemeSolaire = ss.id
              INNER JOIN galaxie g ON ss.idGalaxie = g.id
              INNER JOIN univers u ON g.idUnivers = u.id
              INNER JOIN infrastructure i ON p.id = i.idPlanete
              WHERE p.idJoueur = :idJoueur AND u.id = :idUnivers AND ss.idGalaxie IN (SELECT id FROM galaxie WHERE idUnivers = :idUnivers)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
    $infoNiveau = $stmt->fetchAll();
    return $infoNiveau;
}



function AjoutRessources($pdo, $idJoueur, $ajoutMetal, $ajoutEnergie, $ajoutDeuterium)
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
    $Metal = $stockMetal + $ajoutMetal;
    $Energie = $stockEnergie + $ajoutEnergie;
    $Deuterium = $stockDeuterium + $ajoutDeuterium;
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

function calculeTemps($pdo, $idJoueur){
    // Récupération du temps depuis la dernière vérification
    $query = "SELECT temps FROM production WHERE idJoueur = :idJoueur";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $temps = $result['temps'];
    // Calcul du temps écoulé en ms
    $dateActuelle = time();

    $tempsEcoule = $dateActuelle - $temps;
    var_dump($tempsEcoule);
    return $tempsEcoule;
}


function calculProd($pdo, $idJoueur, $idUnivers, $minute, $tableauInfra){
    echo "calcul prod";
    // var_dump($tableauInfra);
    $prodTotalMetal = 0;
    $prodTotalEnergie = 0;
    $prodTotalDeuterium = 0;
    foreach ($tableauInfra as $niveau) {
        // Calcul de la production de métal
        // Récupération production par minute en fonction du niveau de l'usine de métal
        $query = "SELECT production FROM prod_metal WHERE niveau = :niveauUsineMetal";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':niveauUsineMetal', $niveau['niveauUsineMetal']);
        $stmt->execute();
        $prodMetal = $stmt->fetchColumn();        
        // Calcul de la production de métal en fonction du niveau de l'usine de métal
        $prodMetal = $prodMetal * $minute;

        // Calcul de la production d'énergie
        // Récupération production par minute en fonction du niveau des usines associé
        $query = "SELECT production FROM prod_solaire WHERE  niveau = :niveauCentraleSolaire";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':niveauCentraleSolaire', $niveau['niveauCentraleSolaire']);
        $stmt->execute();
        $prodEnergie = $stmt->fetchColumn(); 

        $query = "SELECT production FROM prod_fusion WHERE  niveau = :niveauCentraleFusion";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':niveauCentraleFusion', $niveau['niveauCentraleFusion']);
        $stmt->execute();
        $prodEnergieFusion = $stmt->fetchColumn(); 
        $prodEnergie += $prodEnergieFusion;

        // Calcul de la production d'énergie en fonction du niveau des usines associé
        $prodEnergie = $prodEnergie * $minute;

        // Calcul de la production de deutérium
        // Récupération production par minute en fonction du niveau de l'usine de synthétiseur de deutérium
        $query = "SELECT production FROM prod_deuterium WHERE niveau = :niveauSynthetiseurDeut";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':niveauSynthetiseurDeut', $niveau['niveauSynthetiseurDeut']);
        $stmt->execute();
        $prodDeuterium = $stmt->fetchColumn(); 
        // Calcul de la production de deutérium en fonction du niveau de l'usine de synthétiseur de deutérium
        $prodDeuterium = $prodDeuterium * $minute;
        echo "on arrive la";
        var_dump($prodDeuterium);
        // Ajout des prodution au total produit
        $prodTotalDeuterium += $prodDeuterium;
        $prodTotalEnergie += $prodEnergie;
        $prodTotalMetal += $prodMetal;
    }
    $tableInfo = array('metal' => $prodTotalMetal, 'energie' => $prodTotalEnergie, 'deuterium' => $prodTotalDeuterium);
    return $tableInfo;

}

function miseAJourTemps($pdo, $idJoueur){
    $date = time();
    $query = "UPDATE production SET temps = :date WHERE idJoueur = :idJoueur";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->execute();
}
?>