<?php
if (!isset($_SESSION)) {
    session_start();
}

// connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

// Récupération de l'ID de l'utilisateur connecté depuis la session
$idJoueur = $_SESSION['idJoueur'];

// Récupération des informations des vaisseaux de l'utilisateur depuis la base de données
$stmtVaisseaux = $pdo->prepare('SELECT * FROM flotte WHERE idJoueur = :idJoueur');
$stmtVaisseaux->execute(['idJoueur' => $idJoueur]);
$vaisseaux = $stmtVaisseaux->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <title>Gestion des flottes</title>
        <link rel="stylesheet" href="../style/css_index.css" />
    </head>
    <body>
        <h1>Gestion des flottes</h1>
        <?php foreach ($vaisseaux as $vaisseau) {
            echo '<h3>' . $vaisseau['typeVaisseau'] . '</h3>';
            echo '<p>Nombre : ' . $vaisseau['flotte'] . '</p>';
            echo '<br>';
        } ?>

    </body>
</html>