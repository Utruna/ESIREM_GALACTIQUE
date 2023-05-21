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
$coutMetalColo= !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieColo = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumColo = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

?>

<!DOCTYPE html>
<header>
    <meta charset="utf-8" />
    <title>Chantier Spatial</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</header>

<body>
    <div>
        <form action="./chasseur.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>Chasseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_chasseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalChasseur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieChasseur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumChasseur ?></p>
            <p class="resource">Temps de construction : 20 seconde</p>
            <p>Point d'attaque : 75</p>
            <p>Point de défense : 50</p>
            <input type="text" name="chasseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonEnergie" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./croiseur.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>Croiseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_croiseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalCroiseur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieCroiseur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumCroiseur ?></p>
            <p class="resource">Temps de construction : 120 seconde</p>
            <p>Point d'attaque : 400</p>
            <p>Point de défense : 150</p>
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonEnergie" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./transporteur.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>Transporteur</h2>
            <h3>Disponible : <?php echo $flotte['nb_transporteur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalTransporteur ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieTransporteur ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumTransporteur ?></p>
            <p class="resource">Temps de construction : 55 seconde</p>
            <p>Point d'attaque : 50</p>
            <p>Point de défense : 100 000</p>
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonEnergie" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
    <form action="./coloniseur.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>coloniseur</h2>
            <h3>Disponible : <?php echo $flotte['nb_coloniseur'] ?></h3>
            <p class="resource">Métal : <?php echo $coutMetalColo ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieColo ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumColo ?></p>
            <p class="resource">Temps de construction : 55 seconde</p>
            <p>Point d'attaque : 50</p>
            <p>Point de défense : 100 000</p>
            <input type="text" name="croiseur" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonEnergie" data-delai="4">Construire</button>
        </form>
    </div>
    <form method="post" action="./../galaxie/galaxie.php">
        <button type="submit">Retour</button>
    </form>
</body>