<?php
if (!isset($_SESSION)) {
    session_start();
}

include 'functions.php';
include "./../univers/alert.php";
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

$idPlanete = $_SESSION['idPlanete'];
$stmt = $pdo->prepare("SELECT * FROM planete WHERE id = :idPlanete");
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$planete = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>

<body>
<h2>Vous êtes sur la Planete : <?php echo $planete['nom']; ?></h2>
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

</body>