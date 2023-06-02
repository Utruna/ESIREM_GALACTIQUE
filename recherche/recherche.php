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

// =================== ENERGIE ===================
if (isset($_SESSION['energie'])) {
    upgradeEnergie($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechEnergie FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauEnergie = $stmt->fetch(PDO::FETCH_ASSOC);
if($niveauEnergie['niveauTechEnergie']){

}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_energie'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($cout);
// Récupérer les coûts
$coutMetalEnergie = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieEnergie = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumEnergie = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== LASER ===================
if (isset($_SESSION['laser'])) {
    upgradeLaser($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechLaser FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauLaser = $stmt->fetch(PDO::FETCH_ASSOC);

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_laser'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalLaser = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieLaser = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumLaser = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== IONS ===================
if (isset($_SESSION['ions'])) {
    upgradeIons($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechIons FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauIons = $stmt->fetch(PDO::FETCH_ASSOC);

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_ions'";
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
$query = "SELECT niveauTechBouclier FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauBouclier = $stmt->fetch(PDO::FETCH_ASSOC);

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_bouclier'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalBouclier = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieBouclier = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumBouclier = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== ARMEMENT ===================
if (isset($_SESSION['armement'])) {
    upgradeArmement($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechArmement FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauArmement = $stmt->fetch(PDO::FETCH_ASSOC);

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'armement'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalArmement = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieArmement = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumArmement = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== RESSOURCE JOUEUR ===================
$query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";   
$stmt = $pdo->prepare($query);
$stmt->execute(array(':idJoueur' => $idJoueur,':idUnivers' => $idUnivers));
$ressource = $stmt->fetch(PDO::FETCH_ASSOC);
$ressource['metal'] = !empty($ressource['stockMetal']) ? $ressource['stockMetal'] : 0;
$ressource['energie'] = !empty($ressource['stockEnergie']) ? $ressource['stockEnergie'] : 0;
$ressource['deuterium'] = !empty($ressource['stockDeuterium']) ? $ressource['stockDeuterium'] : 0;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recherche</title>
    <link rel="stylesheet" href="../style/css_recherche.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <img src="../img/arriere_plan_recherche.jpg" class="arriere_plan" />
    <div class="objet_1">
        <img src="../img/symbole_energie.png"/>
        <div>
            <h2>Energie</h2>
            <h3>Niveau actuel : <?php echo $niveauEnergie['niveauTechEnergie'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalEnergie ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieEnergie ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumEnergie ?></p>
            <p>Production d’énergie augmentée de 2%</p>
            <form action="./energie.php" method="post">
                <input type="text" name="energie" value="true" style="display: none">
                <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
                <button class="bouton" type="submit" name="boutonEnergie" data-delai="4">Rechercher (4 secondes)</button>
            </form>
        </div>
    </div>
    <div class="objet_2">
        <img src="../img/symbole_laser.png"/>
        <div>
            <h2>Laser</h2>
            <h3>Niveau actuel : <?php echo $niveauLaser['niveauTechLaser'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalLaser ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieLaser ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumLaser ?></p>
            <form action="./laser.php" method="post">
                <input type="text" name="laser" value="true" style="display: none">
                <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
                <button class="bouton" type="submit" name="boutonLaser" data-delai="4">Rechercher (2 secondes)</button>
            </form>
        </div>
    </div>
    <div class="objet_3">
        <img src="../img/symbole_ions.png"/>
        <div>
            <h2>Ions</h2>
            <h3>Niveau actuel : <?php echo $niveauIons['niveauTechIons'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalIons ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieIons ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumIons ?></p>
            <form action="./ions.php" method="post">
                <input type="text" name="ions" value="true" style="display: none">
                <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
                <button class="bouton" ltype="submit" name="boutonIons" data-delai="4">Rechercher (8 secondes)</button>
            </form>
        </div>
    </div>
    <div class="objet_4">
        <img src="../img/symbole_bouclier.png"/>
        <div>
            <h2>Bouclier</h2>
            <h3>Niveau actuel : <?php echo $niveauBouclier['niveauTechBouclier'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalBouclier ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieBouclier ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumBouclier ?></p>
            <form action="./bouclier.php" method="post">
                <input type="text" name="bouclier" value="true" style="display: none">
                <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
                <button class="bouton" type="submit" name="boutonIons" data-delai="4">Rechercher (5 secondes)</button>
            </form>
        </div>
    </div>
    <div class="objet_5">
        <img src="../img/symbole_armement.png"/>
        <div>
            <h2>Armement</h2>
            <h3>Niveau actuel : <?php echo $niveauArmement['niveauTechArmement'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalArmement ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieArmement ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumArmement ?></p>
            <form action="./armement.php" method="post">
                <input type="text" name="armement" value="true" style="display: none">
                <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
                <button class="bouton" type="submit" name="boutonIons" data-delai="4">Rechercher (5 secondes)</button>
            </form>
        </div>
    </div>
    <div class="div_ressources">
        <h2>Ressource Joueur</h2>
        <p class="resource">Métal : <?php echo $ressource['metal'] ?></p>
        <p class="resource">Energie : <?php echo $ressource['energie'] ?></p>
        <p class="resource">Deutérium : <?php echo $ressource['deuterium'] ?></p>
        <form method="post" action="./../univers/prod.php">
            <button class="bouton" type="submit">Produire</button>
        </form>
    </div>
    <form method="post" action="./../galaxie/manager.php">
        <button class="retour" type="submit">Retour</button>
    </form>
</body>
</html>