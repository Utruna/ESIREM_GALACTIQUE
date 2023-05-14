<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
$idPlanete = $_SESSION['idPlanete'];
var_dump($idPlanete);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Recherche</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('button[name="button"]').click(function() {
                $.ajax({
                    url: 'fonction.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.result) {
                            alert('Les recherches nécessaires sont satisfaites.');
                            // Effectuer d'autres actions si nécessaire
                        } else {
                            alert('Les recherches nécessaires ne sont pas satisfaites.');
                            // Effectuer d'autres actions si nécessaire
                        }
                    },
                    error: function() {
                        alert('Une erreur s\'est produite lors de la vérification des recherches.');
                        // Gérer l'erreur si nécessaire
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div>
        <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
        <h2>Energie</h2>
        <p class="resource">Deutérium: 2 000</p>
        <p class="resource">Temps de construction : 4 seconde</p>
        <p>Production d’énergie augmenter de 2%</p>
        <button type="button" name="button">Rechercher</button>
    </div>
    <div>
        <!-- <img src="./../img/energie.jpg" alt="planete" /> -->
        <h2>Laser</h2>
        <p class="resource">Deutérium: <?php ?></p>
        <p class="resource">Temps de construction : 8 seconde</p>
        <?php

        ?>
        <button type="button" name="button">Rechercher</button>
    </div>
</body>

</html>