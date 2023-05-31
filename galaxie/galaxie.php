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

// Liste planete du joueur
$planeteJoueur = getPlaneteJoueur($pdo, $_SESSION['idJoueur'], $idUnivers);

// Récupération information flotte 
$flotte = getFlotte($pdo, $_SESSION['idJoueur']);

//var_dump($planeteJoueur);
?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <!-- <link rel="stylesheet" href="../style/css_index.css" /> -->
    <link rel="stylesheet" href="../style/popupGalaxie.css" />
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
                                    <button type="submit">Manager</button>
                                </form>

                            <?php } else {
                            ?>
                            <!-- ================ AQUERIRE = TRICHE ================ -->
                                <!-- <div>
                                    <form action="./aquerire_planete.php" method="post">
                                        <input type="hidden" name="idPlanete" value="<?php echo $planete['id']; ?>">
                                        <button type="submit">Aquerire</button>
                                    </form>
                                </div> -->

                                <button class="attaquer-planete" data-planete-id="<?php echo $planete['id']; ?>">
                                    Attaquer
                                </button>

                                <div id="dialog-<?php echo $planete['id']; ?>" class="dialog-overlay">
                                    <div class="dialog-box">
                                        <form action="attaquer">
                                            Vous voulez attaquer la planete : <?php echo $planete['nom']; ?>
                                            Selectionner la planete avec laquel vous voulez attaquer :
                                            <input type="hidden" name="planeteAttaquee" value="<?php echo $planete['id']; ?>">
                                            <select name="planeteAttaquante">
                                                <?php foreach ($planeteJoueur as $planeteJ) { ?>
                                                    <option value="<?php echo $planeteJ['id']; ?>"><?php echo $planeteJ['nom']; ?></option>
                                                <?php } ?>
                                            </select>

                                            <table>
                                                <ul>
                                                    <li>
                                                        <img src="./../img/chasseur.png" style="width:25%" alt="">
                                                        <label for="flot-1">Chassseur</label>
                                                        <input name="flot-1" type="number">
                                                        Chasseur disponible : <?php echo $flotte['nb_chasseur']; ?>
                                                    </li>
                                                    <li>
                                                        <img src="./../img/croiseur.png" style="width:25%" alt="">
                                                        <label for="flot-1">Croiseur</label>
                                                        <input name="flot-1" type="number">
                                                        Croisuer disponible : <?php echo $flotte['nb_croiseur']; ?>
                                                    </li>
                                                    <li>
                                                        <img src="./../img/transporteur.png" style="width:25%" alt="">
                                                        <label for="flot-1">Transporteur</label>
                                                        <input name="flot-1" type="number">
                                                        Transporteur disponible : <?php echo $flotte['nb_transporteur']; ?>
                                                    </li>
                                                    <li>
                                                        <img src="./../img/coloniseur.png" style="width:25%" alt="">
                                                        <label for="flot-1">Coloniseur</label>
                                                        <input name="flot-1" type="number">
                                                        Coloniseur disponible : <?php echo $flotte['nb_coloniseur']; ?>
                                                    </li>
                                                </ul>
                                                <ul>
                                                    <li></li>
                                                    <li>
                                                        <button type="submit"> Attaquer</button>
                                                    </li>
                                                    <li>
                                                        <button class="dialog-close" type="button" onclick="closeDialog('<?php echo $planete['id']; ?>')" data-planete-id="<?php echo $planete['id']; ?>"> Annuler</button>
                                                    </li>
                                                    <li></li>
                                                </ul>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <form method="post" action="./../deconection.php">
        <button type="submit">Déconnexion</button>
    </form>

    <script>
        function openDialog(dialogId) {
            var dialog = document.getElementById(dialogId);
            dialog.style.display = 'flex';
        }

        function closeDialog(dialogId) {
            var dialog = document.getElementById(dialogId);
            dialog.style.display = 'none';
        }

        function initDialogButtons() {
            var buttons = document.getElementsByClassName('attaquer-planete');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function() {
                    var planeteId = this.getAttribute('data-planete-id');
                    openDialog('dialog-' + planeteId);
                });
            }
        }

        function initCloseButtons() {
            var closeButtons = document.getElementsByClassName('dialog-close');
            for (var i = 0; i < closeButtons.length; i++) {
                closeButtons[i].addEventListener('click', function() {
                    var planeteId = this.getAttribute('data-planete-id');
                    closeDialog('dialog-' + planeteId);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initDialogButtons();
            initCloseButtons();
        });
    </script>
</body>

</html>