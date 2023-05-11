<?php
if (!isset($_SESSION)) {
    session_start();
}

include 'functions.php'; 
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

// Récupération de l'ID de l'univers choisi
$idUnivers = $_SESSION['idUnivers'];


?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>

<body>   <h2>Vous êtes dans l'univers <?php echo getUnivers($pdo, $idUnivers)['nom']; ?></h2>
    <h2>Sélectionner une galaxie et un système solaire</h2>
    <form method="get">
        <div>
            <label>Galaxie :</label>
            <select name="galaxie" onchange="this.form.submit()">
                <?php foreach (getGalaxies($pdo, $idUnivers) as $galaxie) : ?>
                    <option value="<?php echo $galaxie['id']; ?>"><?php echo $galaxie['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Système solaire :</label>
            <select name="systeme-solaire" onchange="this.form.submit()">
                <?php
                $galaxieId = $_GET['galaxie'] ?? null;
                foreach (getSystemesSolaire($pdo, $galaxieId) as $systeme) : ?>
                    <option value="<?php echo $systeme['id']; ?>"><?php echo $systeme['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Nom de la planète</th>
                        <th>Joueur</th>
                        <th>Type</th>
                        <th>Position</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $systemeSolaireId = $_GET['systeme-solaire'] ?? null;
                    foreach (getPlanetes($pdo, $systemeSolaireId) as $planete) : ?>
                        <tr>
                            <td><?php echo $planete['nom']; ?></td>
                            <td><?php echo getJoueur($pdo, $planete['idJoueur'])['nom']; ?></td>
                            <td><?php echo getTypePlanete($pdo, $planete['idType'])['nom']; ?></td>
                            <td><?php echo $planete['position']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
    <script src="./../js/controllers/applicationGalaxie.js" type="module"></script>
</body>

</html>