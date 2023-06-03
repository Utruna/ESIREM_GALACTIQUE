<?php
if (!isset($_SESSION)) {
    session_start();
}

include "./../univers/alert.php";
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

$idJoueur = $_SESSION['idJoueur'];
$idPlanete = $_SESSION['idPlanete'];
$idUnivers = $_SESSION['idUnivers'];


$query = "SELECT * FROM flotte WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$flotte = $stmt->fetch(PDO::FETCH_ASSOC);
// =================== CHASSEUR ===================
if (isset($_POST['chasseur'])) {
    getChasseur($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'chasseur'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalChasseur = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieChasseur = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumChasseur = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;


// =================== CROISEUR ===================
if (isset($_POST['croiseur'])) {
    getCroiseur($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'croiseur'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalCroiseur = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieCroiseur = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumCroiseur = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== TRANSPORTEUR ===================
if (isset($_POST['transporteur'])) {
    getTransporteur($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'transporteur'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalTransporteur = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieTransporteur = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumTransporteur = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== VAISSEAU DE COLONISATION ===================
if (isset($_POST['vaisseauDeColonisation'])) {
    getcoloniseur($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'vaisseau_de_colonisation'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalColo = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieColo = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumColo = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;


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
    <title>Chantier Spatial</title>
    <link rel="stylesheet" href="../style/css_chantier.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <img src="../img/arriere_plan_chantier.jpg" class="arriere_plan">
    <div class="objet">
        <img src="./../img/chasseur.png" alt="">
        <div>
            <h2>Chasseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_chasseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalChasseur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieChasseur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumChasseur ?></p>
            <p>Point d'attaque : 75</p>
            <p>Point de défense : 50</p>
        </div>
        <form action="./chasseur.php" method="post">
            <input type="text" name="chasseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button class="bouton" type="submit" name="boutonEnergie" data-delai="4">Construire (20 secondes)</button>
        </form>
    </div>
    <div class="objet">
        <img src="./../img/croiseur.png" alt="">
        <div>
            <h2>Croiseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_croiseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalCroiseur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieCroiseur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumCroiseur ?></p>
            <p>Point d'attaque : 400</p>
            <p>Point de défense : 150</p>
        </div>
        <form action="./croiseur.php" method="post">
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button class="bouton" type="submit" name="boutonEnergie" data-delai="4">Construire (120 secondes)</button>
        </form>
    </div>
    <div class="objet">
        <img src="./../img/transporteur.png" alt="">
        <div>
            <h2>Transporteur</h2>
            <h3>Disponible : <?php echo $flotte['nb_transporteur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalTransporteur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieTransporteur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumTransporteur ?></p>
            <p>Point d'attaque : 50</p>
            <p>Point de défense : 100 000</p>
        </div>
        <form action="./transporteur.php" method="post">
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button class="bouton" type="submit" name="boutonEnergie" data-delai="4">Construire (55 secondes)</button>
        </form>
    </div>
    <div class="objet">
        <img src="./../img/coloniseur.png" alt="">
        <div>
            <h2>Coloniseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_coloniseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalColo ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieColo ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumColo ?></p>
            <p>Point d'attaque : 50</p>
            <p>Point de défense : 100 000</p>
        </div>
        <form action="./coloniseur.php" method="post">
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button class="bouton" type="submit" name="boutonEnergie" data-delai="4">Construire (55 secondes)</button>
        </form>
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