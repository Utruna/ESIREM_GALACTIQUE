<?php
if (!isset($_SESSION)) {
    session_start();
}
include './../univers/alert.php';
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
if (isset($_POST['idPlanete'])) {
    $idPlanete = $_POST['idPlanete'];
    $_SESSION['idPlanete'] = $idPlanete;
} else {
    $idPlanete = $_SESSION['idPlanete'];
}


$query = "SELECT * FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== ARTILLERIE LASER ===================
if (isset($_SESSION['laser'])) {
    upgradeArtillerieLaser($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'artillerie_laser'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalLaser = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieLaser = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumLaser = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CANON A IONS ===================
if (isset($_SESSION['ions'])) {
    upgradeCanonAIons($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'cannon_a_ions'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalIons = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieIons = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumIons = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== BOUCLIER ===================
if (isset($_SESSION['bouclier'])) {
    upgradeBouclier($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'bouclier'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalBouclier = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieBouclier = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumBouclier = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== RESSOURCE JOUEUR ===================
$query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':idJoueur' => $idJoueur, ':idUnivers' => $idUnivers));
$ressource = $stmt->fetch(PDO::FETCH_ASSOC);
$ressource['metal'] = !empty($ressource['stockMetal']) ? $ressource['stockMetal'] : 0;
$ressource['energie'] = !empty($ressource['stockEnergie']) ? $ressource['stockEnergie'] : 0;
$ressource['deuterium'] = !empty($ressource['stockDeuterium']) ? $ressource['stockDeuterium'] : 0;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Système de défense</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form action="./labo.php" method="post">
            <h2>Artilllerie laser</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauArtillerieLaser'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalLaser ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieLaser ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumLaser ?></p>
            <p class="resource">Temps de construction : 10 seconde</p>
            <input type="text" name="laser" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonLaser" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./ions.php" method="post">
            <h2>Canon a ions</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauCannonIons'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalIons ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieIons ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumIons ?></p>
            <p class="resource">Temps de construction : 10 seconde</p>
            <input type="text" name="laser" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonLaser" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./bouclier.php" method="post">
            <h2>Bouclier</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauBouclier'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalBouclier ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieBouclier ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumBouclier ?></p>
            <p class="resource">Temps de construction : 60 seconde</p>
            <input type="text" name="bouclier" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonBouclier" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <h2>Ressource Joueur</h2>
        <p class="resource">Métal : <?php echo $ressource['metal'] ?></p>
        <p class="resource">Energie : <?php echo $ressource['energie'] ?></p>
        <p class="resource">Deutérium : <?php echo $ressource['deuterium'] ?></p>
        <form method="post" action="./../univers/prod.php">
            <button class="bouton" type="submit">Produire</button>
        </form>
    </div>
    <form method="post" action="./../galaxie/manager.php">
        <button type="submit">Retour</button>
    </form>
</body>

</html>