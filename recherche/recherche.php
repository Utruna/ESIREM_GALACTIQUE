<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];


?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <title>Recherche</title>
    <link rel="stylesheet" href="../style/css_index.css" />
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
        <p class="resource">Deutérium: 300</p>
        <p class="resource">Temps de construction : 8 seconde</p>
        <?php 
        
        ?>
        <button type="button" name="button">Rechercher</button>
    </div>

</body>