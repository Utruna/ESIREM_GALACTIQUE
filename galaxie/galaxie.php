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
                    <th>Nom de la planète</th>
                    <th>Joueur</th>
                    <th>Type</th>
                    <!-- <th>Position</th> -->
                    <th>Manager</th>
                    <th>Acquérir</th>
                    <th>Recherche</th>
                    <th>Chantier Spatial</th>
                    <th>infrastructure</th>
                </tr>
            </thead>
            <tbody>
                <!-- On souhaite afficher uniquement les planètes existantes, donc nous utilisons la même méthode que précédemment -->
                <?php foreach ($planetes as $planete) { ?>
                    <tr>
                        <td><?php echo $planete['nom']; ?></td>
                        <td><?php echo getJoueur($pdo, $planete['idJoueur'])['nom']; ?></td>
                        <td><?php echo getTypePlanete($pdo, $planete['idType']); ?></td>
                        <!-- <td><?php echo $planete['position']; ?></td> -->
                        <td>
                            <?php
                            // Si la planète appartient au joueur connecté, affiche un lien vers la page de gestion de la planète
                            if ($planete['idJoueur'] == $_SESSION['idJoueur']) {
                                echo '<a href="planete.php?planete=' . $planete['id'] . '">Gérer</a>';
                            } else {
                                echo 'Non';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($planete['idJoueur'] == $_SESSION['idJoueur']) { ?>
                                <button class="acquerir-planete" data-id-planete="<?php echo $planete['id']; ?>">Acquérir</button>
                            <?php
                            } else { ?>
                                <button class="acquerir-planete" data-id-planete="<?php echo $planete['id']; ?>">Acquérir</button>
                                <!-- <button class="attaquer-planete"
                        onclick="document.getElementById('attaque-<?php echo $planete["id"]; ?>').style.display = 'block'">
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
                                        </li>                                        <li>
                                            <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                            <label for="flot-1">Chassseur</label>
                                            <input name="flot-1" type="number">
                                        </li>                                        <li>
                                            <img src="https://cdn-icons-png.flaticon.com/512/5219/5219396.png" style="width:25%" alt="">
                                            <label for="flot-1">Chassseur</label>
                                            <input name="flot-1" type="number">
                                        </li>                                        <li>
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
                                            <button type="button"
                                                    onclick="this.display = 'none'"
                                            > Annuler</button>
                                        </li>
                                        <li></li>
                                    </ul>
                                </table>
                            </form> -->

    </div>
<?php } ?>
</td>
<td>
    <form action="./../recherche/recherche.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
        <?php if ($planete['idJoueur'] == $_SESSION['idJoueur']) { ?>
            <button type="submit">Rechercher</button><?php } ?>
    </form>
</td>
<td>
    <form action="./../ChantierSpatial/chantier.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
        <?php if ($planete['idJoueur'] == $_SESSION['idJoueur']) { ?>
            <button type="submit">Chantier Spatial</button><?php } ?>
    </form>
</td>
<td>
    <form action="./../infrastructure/infrastructure.php" method="post">
        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
        <?php if ($planete['idJoueur'] == $_SESSION['idJoueur']) { ?>
            <button type="submit">Infrastructure</button><?php } ?>
    </form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<form method="post" action="./../deconection.php">
    <button type="submit">Déconnexion</button>
</form>
<?php echo '<a href="./../flotte/flotte.php">gestion flotte</a>' ?>
</body>

<script>
    // Récupération de tous les boutons d'acquisition de planète
    var acquerirPlaneteButtons = document.getElementsByClassName('acquerir-planete');

    // Parcourir tous les boutons et ajouter un gestionnaire d'événements à chacun
    for (var i = 0; i < acquerirPlaneteButtons.length; i++) {
        acquerirPlaneteButtons[i].addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du bouton (rechargement de la page)

            // Récupération de l'ID de la planète à partir de l'attribut personnalisé
            var idPlanete = this.getAttribute('data-id-planete');

            // Envoi de la requête AJAX au serveur
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'aquerire_planete.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText); // Affiche la réponse dans la console du navigateur
                    // Rechargement de la page
                    location.reload();
                }
            };
            xhr.send('idPlanete=' + encodeURIComponent(idPlanete));
        });
    }
</script>

</html>