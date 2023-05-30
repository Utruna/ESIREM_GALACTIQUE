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
// var_dump($idPlanete);
// =================== RESSOURCE JOUEUR ===================
$query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";   
$stmt = $pdo->prepare($query);
$stmt->execute(array(':idJoueur' => $idJoueur,':idUnivers' => $idUnivers));
$ressource = $stmt->fetch(PDO::FETCH_ASSOC);
$ressource['metal'] = !empty($ressource['metal']) ? $ressource['metal'] : 0;
$ressource['energie'] = !empty($ressource['energie']) ? $ressource['energie'] : 0;
$ressource['deuterium'] = !empty($ressource['deuterium']) ? $ressource['deuterium'] : 0;


?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>

<body>
    <form action="./../recherche/recherche.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button type="submit">Rechercher</button>
    </form>
    <form action="./../ChantierSpatial/chantier.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button type="submit">Chantier Spatial</button>
    </form>
    <form action="./../infrastructure/infrastructure.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button type="submit">Infrastructure</button>
    </form>
    <form action="./../defense/defense.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $idPlanete; ?>">
            <button type="submit">Defense</button>
    </form>
    <form method="post" action="./galaxie.php">
        <button type="submit">Retour</button>
    </form>
    <div>
        <h2>Ressource Joueur</h2>
        <p class="resource">Métal : <?php echo $ressource['metal'] ?></p>
        <p class="resource">Energie : <?php echo $ressource['energie'] ?></p>
        <p class="resource">Deutérium : <?php echo $ressource['deuterium'] ?></p>
    </div>
</body>