<?php
if (!isset($_SESSION)) {
    session_start();
}
include './../univers/alert.php';
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
if (isset($_POST['idPlanete'])) {
    $idPlanete = $_POST['idPlanete'];
    $_SESSION['idPlanete'] = $idPlanete;
} else {
    $idPlanete = $_SESSION['idPlanete'];
}

// =================== RECHERCHE ===================
if (isset($_SESSION['energie'])) {
    upgradeEnergie($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechEnergie FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauEnergie = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== LASER ===================
if (isset($_SESSION['laser'])) {
    upgradeLaser($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechLaser FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauLaser = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== IONS ===================
if (isset($_SESSION['ions'])) {
    upgradeIons($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechIons FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauIons = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== BOUCLIER ===================
if (isset($_SESSION['bouclier'])) {
    upgradeBouclier($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechBouclier FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauBouclier = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== ARMEMENT ===================
if (isset($_SESSION['armement'])) {
    upgradeArmement($idJoueur, $idPlanete);
}
$query = "SELECT niveauTechArmement FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$niveauArmement = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recherche</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form action="./energie.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>Energie</h2>
            <h3>Niveau actuel : <?php echo $niveauEnergie['niveauTechEnergie'] ?>/10</h3>
            <p class="resource">Deutérium: 2 000</p>
            <p class="resource">Temps de construction : 4 seconde</p>
            <p>Production d’énergie augmenter de 2%</p>
            <input type="text" name="energie" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonEnergie" data-delai="4">Rechercher</button>
        </form>
    </div>
    <div>
        <form action="./laser.php" method="post">
            <!-- <img src="./../img/laser.jpg" alt="planete" /> -->
            <h2>laser</h2>
            <h3>Niveau actuel : <?php echo $niveauLaser['niveauTechLaser'] ?>/10</h3>
            <p class="resource">Deutérium: 300</p>
            <p class="resource">Temps de construction : 2 seconde</p>
            <input type="text" name="laser" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonLaser" data-delai="4">Rechercher</button>
        </form>
    </div>
    <div>
        <form action="./ions.php" method="post">
            <!-- <img src="./../img/laser.jpg" alt="planete" /> -->
            <h2>Ions</h2>
            <h3>Niveau actuel : <?php echo $niveauIons['niveauTechIons'] ?>/10</h3>
            <p class="resource">Deutérium: 500 </p>
            <p class="resource">Temps de construction : 8 seconde</p>
            <input type="text" name="ions" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonIons" data-delai="4">Rechercher</button>
        </form>
    </div>
    <div>
        <form action="./bouclier.php" method="post">
            <!-- <img src="./../img/laser.jpg" alt="planete" /> -->
            <h2>Bouclier</h2>
            <h3>Niveau actuel : <?php echo $niveauBouclier['niveauTechBouclier'] ?>/10</h3>
            <p class="resource">Deutérium: 1000 </p>
            <p class="resource">Temps de construction : 5 seconde</p>
            <input type="text" name="bouclier" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonIons" data-delai="4">Rechercher</button>
        </form>
    </div>
    <div>
        <form action="./armement.php" method="post">
            <!-- <img src="./../img/laser.jpg" alt="planete" /> -->
            <h2>Armee</h2>
            <h3>Niveau actuel : <?php echo $niveauArmement['niveauTechArmement'] ?>/10</h3>
            <p class="resource">Deutérium: 1000 </p>
            <p class="resource">Temps de construction : 5 seconde</p>
            <input type="text" name="armement" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonIons" data-delai="4">Rechercher</button>
        </form>
    </div>
</body>

</html>

<script>
    // $(document).ready(function() {
    //     $('button[name="bouton"]').click(function() {
    //         var bouton = $(this); // Stockez une référence au bouton cliqué
    //
    //         bouton.prop('disabled', true); // Désactivez le bouton pendant le délai
    //
    //         // Récupérez la durée du délai à partir de l'attribut data-delai
    //         var delai = parseInt(bouton.data('delai'));
    //
    //         // Affichez un message d'attente
    //         alert('Recherche en cours. Veuillez patienter...');
    //
    //         //  On effectue la recherch ene utilisant ajax
    //         $.ajax({
    //             url: 'fonction.php',
    //             type: 'POST',
    //             dataType: 'json',
    //             success: function(response) {
    //                 if (response.result) {
    //                     alert('Les recherches nécessaires sont satisfaites.');
    //                     // Effectuez d'autres actions si nécessaire
    //                 } else {
    //                     alert('Les recherches nécessaires ne sont pas satisfaites.');
    //                     // Effectuez d'autres actions si nécessaire
    //                 }
    //             },
    //             error: function() {
    //                 alert('Une erreur s\'est produite lors de la vérification des recherches.');
    //                 // Gérez l'erreur si nécessaire
    //             },
    //             complete: function() {
    //                 bouton.prop('disabled', false); // Réactivez le bouton après la recherche
    //             }
    //         });
    //     });
    // });
</script>