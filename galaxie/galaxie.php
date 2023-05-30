<?php
if (!isset($_SESSION)) {
    session_start();
}

include 'functions.php';
include "./../univers/alert.php";
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

// Récupération de l'ID de l'univers choisi
$idUnivers = $_SESSION['idUnivers'];

// Récupération des données de l'univers choisi
$univers = getUnivers($pdo, $idUnivers);

// Récupération des galaxies de l'univers choisi
$galaxies = getGalaxies($pdo, $idUnivers);

// Récupération de l'ID de la galaxie sélectionnée
$galaxieId = $_GET['galaxie'] ?? $galaxies[0]["id"];

// Récupération des systèmes solaires de la galaxie choisie
$systemesSolaire = getSystemesSolaire($pdo, $galaxieId);

// Récupération de l'ID du système solaire sélectionné
$systemeSolaireId = $_GET['systeme-solaire'] ?? $systemesSolaire[0]["id"];

// Récupération des planètes du système solaire choisi
$planetes = getPlanetes($pdo, $systemeSolaireId);

?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>

<body>
    <h2>Vous êtes dans l'univers <?php echo $univers['nom']; ?></h2>
    <h2>Sélectionner une galaxie et un système solaire</h2>

    <form method="get">
        <div>
            <label>Galaxie :</label>
            <select name="galaxie" onchange="this.form.submit()">
                <?php foreach ($galaxies as $galaxie) : // Affichage de chaque galaxie dans une liste
                ?>
                    <option value="<?php echo $galaxie['id']; ?>" <?php if ($galaxieId == $galaxie['id'])
                                                                        echo 'selected'; ?>><?php echo $galaxie['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Système solaire :</label>
            <select name="systeme-solaire" onchange="this.form.submit()">
                <?php foreach ($systemesSolaire as $systeme) : // Affichage de chaque système solaire dans une liste
                ?>
                    <option value="<?php echo $systeme['id']; ?>" <?php if ($systemeSolaireId == $systeme['id']) echo 'selected'; ?>><?php echo $systeme['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <div id="TablePlanetes">
        <table>
            <thead>
                <tr>
                    <th>Planete</th>
                    <th>Joueur</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- On souhaite afficher uniquement les planètes existantes, donc nous utilisons la même méthode que précédemment -->
                <?php foreach ($planetes as $planete) { ?>
                    <tr>
                        <td><?php echo $planete['nom']; ?></td>
                        <td><?php echo getJoueur($pdo, $planete['idJoueur'])['nom']; ?></td>
                        <td><?php echo getTypePlanete($pdo, $planete['idType']); ?></td>
                        <td>
                            <?php
                            // Si la planète appartient au joueur connecté, affiche un lien vers la page de gestion de la planète
                            if ($planete['idJoueur'] == $_SESSION['idJoueur']) {
                            ?>
                                <form action="./manager.php" method="post">
                                    <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
                                    <?php if ($planete['idJoueur'] == $_SESSION['idJoueur']) { ?>
                                        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
                                        <button type="submit">Manager</button><?php } ?>
                                </form>
                            <?php } else {
                            ?>
                                <div>
                                    <form action="./aquerire_planete.php" form="post">
                                        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
                                        <button type="submit">Aquerire</button>
                                    </form>
                                </div>
                                <!-- <button class="attaquer-planete" onclick="document.getElementById('attaque-<?php echo $planete["id"]; ?>').style.display = 'block'">
                                    Attaquer
                                </button>
                                <div style="display: none" id="attaque-<?php echo $planete['id']; ?>">
                                    <form action="attaquer">
                                        <input type="text" name="id-planete" value="<?php echo $planete['id']; ?>">
                                        <table>
                                            <ul>
                                                <li>
                                                    <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                                    <label for="flot-1">Chassseur</label>
                                                    <input name="flot-1" type="number">
                                                </li>
                                                <li>
                                                    <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                                    <label for="flot-1">Chassseur</label>
                                                    <input name="flot-1" type="number">
                                                </li>
                                                <li>
                                                    <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                                    <label for="flot-1">Chassseur</label>
                                                    <input name="flot-1" type="number">
                                                </li>
                                                <li>
                                                    <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                                    <label for="flot-1">Chassseur</label>
                                                    <input name="flot-1" type="number">
                                                </li>
                                            </ul>
                                            <ul>
                                                <li></li>
                                                <li>
                                                    <button type="submit"> Attaquer</button>
                                                </li>
                                                <li>
                                                    <button type="button" onclick="this.display = 'none'"> Annuler</button>
                                                </li>
                                                <li></li>
                                            </ul>
                                        </table>
                                    </form>
                                </div> -->
                            <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <form method="post" action="./../deconection.php">
        <button type="submit">Déconnexion</button>
    </form>
</body>

</html>