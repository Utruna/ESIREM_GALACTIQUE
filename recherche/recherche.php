<?php
if (!isset($_SESSION)) {
    session_start();
}

$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
$_SESSION['idPlanete'] = $_POST['idPlanete'];
$idPlanete = $_SESSION['idPlanete'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recherche</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form action="./fonctions.php" method="post">
            <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
            <h2>Energie</h2>
            <p class="resource">Deutérium: 2 000</p>
            <p class="resource">Temps de construction : 4 seconde</p>
            <p>Production d’énergie augmenter de 2%</p>
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="bouton" data-delai="4">Rechercher</button>
        </form>
    </div>
    <div>
        <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
        <h2>Laser</h2>
        <p class="resource">Deutérium: <?php ?></p>
        <p class="resource">Temps de construction : 8 seconde</p>
        <?php

        ?>
        <button type="button" name="bouton" data-delai="8">Rechercher</button>
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
