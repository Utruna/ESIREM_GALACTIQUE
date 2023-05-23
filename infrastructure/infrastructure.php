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

// =================== LABORATOIRE DE RECHERCHE ===================
if (isset($_SESSION['laboratoire'])) {
    upgradeLabo($idJoueur, $idPlanete);
}


// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'laboratoire_de_recherche'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalLabo = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieLabo = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumLabo = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CHANTIER SPATIAL ===================
if (isset($_SESSION['chantier'])) {
    upgradeChantier($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'laboratoire_de_recherche'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalChantier = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieChantier  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumChantier  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CHANTIER SPATIAL ===================
if (isset($_SESSION['chantier'])) {
    upgradeNanite($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'usine_de_nanites'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalNanite = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieNanite  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumNanite  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CENTRALE ELECTRIQUE ===================




?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Laboratoire de rechreche</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form action="./labo.php" method="post">
            <h2>Laboratoire de recherche</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauLabo'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalLabo ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieLabo ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumLabo ?></p>
            <p class="resource">Temps de construction : 50 seconde</p>
            <input type="text" name="laboratoire" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonLaboratoire" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./chantier.php" method="post">
            <h2>Chantier spatial</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauChantierSpatial'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalChantier ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieChantier ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumChantier ?></p>
            <p class="resource">Temps de construction : 50 seconde</p>
            <input type="text" name="chantier" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonchantier" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./nanite.php" method="post">
            <h2>Mine de nanite</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauUsineNanite'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalNanite ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieNanite ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumNanite ?></p>
            <p class="resource">Temps de construction : 50 seconde</p>
            <input type="text" name="nanite" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonNanite" data-delai="4">Construire</button>
        </form>
    </div>
    <form method="post" action="./../galaxie/galaxie.php">
        <button type="submit">Retour</button>
    </form>
</body>
</html>
