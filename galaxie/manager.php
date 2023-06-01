<?php
if (!isset($_SESSION)) {
    session_start();
}

include 'functions.php';
include "./../univers/alert.php";
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

if (isset($_POST['idPlanete'])){
    $idPlanete = $_POST['idPlanete'];
    $_SESSION['idPlanete'] = $idPlanete;
}
else if(isset($_SESSION['idPlanete'])){
    $idPlanete = $_SESSION['idPlanete'];
}
else{
    header ('Location: ./galaxie.php');
}
$idUnivers = $_SESSION['idUnivers'];
$idJoueur = $_SESSION['idJoueur'];

// var_dump($idPlanete);
// =================== RESSOURCE JOUEUR ===================
$query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";   
$stmt = $pdo->prepare($query);
$stmt->execute(array(':idJoueur' => $idJoueur,':idUnivers' => $idUnivers));
$ressource = $stmt->fetch(PDO::FETCH_ASSOC);
$ressource['metal'] = !empty($ressource['stockMetal']) ? $ressource['stockMetal'] : 0;
$ressource['energie'] = !empty($ressource['stockEnergie']) ? $ressource['stockEnergie'] : 0;
$ressource['deuterium'] = !empty($ressource['stockDeuterium']) ? $ressource['stockDeuterium'] : 0;

// =================== PLANETE ===================
$query = "SELECT * FROM planete WHERE id = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':idPlanete' => $idPlanete));
$planete = $stmt->fetch(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_manager.css" />
    <link rel="stylesheet" href="../style/alert.css" />
</head>

<body>
    <img src="../img/arriere_plan_planete.jpg" class="arriere_plan">
    <h1>Vous êtes sur la Planète : <?php echo $planete['nom']; ?></h1>
    <div class="objet">
        <p>Modification du nom de la planète :</p>
        <form action="./../galaxie/modif.php" method="post">
            <input type="text" name="nomPlanete" value="<?php echo $planete['nom']; ?>">
            <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button class="bouton" type="submit">Modifier</button>
        </form>
    </div>
    <div class="objet_1">
        <img src="../img/symbole_science.png" alt="Symbole de Recherche"/>
        <form action="./../recherche/recherche.php" method="post">
            <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button class="bouton" type="submit">Aller au laboratoire de recherche</button>
        </form>
    </div>
    <div class="objet_2">
        <img src="../img/symbole_chantier-spatial.png" alt="Symbole de chantier Spatial"/>
        <form action="./../ChantierSpatial/chantier.php" method="post">
            <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button class="bouton" type="submit">Aller au chantier spatial</button>
        </form>
    </div>
    <div class="objet_3">
        <img src="../img/symbole_infrastructure.png" alt="Symbole d'infrastructure"/>
        <form action="./../infrastructure/infrastructure.php" method="post">
            <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button class="bouton" type="submit">Aller aux infrastructures</button>
        </form>
    </div>
    <div class="objet_4">
        <img src="../img/symbole_defense.png" alt="Symbole de defense"/>
        <form action="./../defense/defense.php" method="post">
            <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button class="bouton" type="submit">Aller aux systèmes de defense</button>
        </form>
    </div>
    <form method="post" action="./galaxie.php">
        <button class="retour" type="submit">Retour</button>
    </form>
    <div id="div_ressources">
        <h2>Ressource Joueur</h2>
        <p class="resource">Métal : <?php echo $ressource['metal'] ?></p>
        <p class="resource">Energie : <?php echo $ressource['energie'] ?></p>
        <p class="resource">Deutérium : <?php echo $ressource['deuterium'] ?></p>
    </div>
</body>