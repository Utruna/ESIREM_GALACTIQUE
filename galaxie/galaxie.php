<?php
if (!isset($_SESSION)) {
    session_start();
}
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

// Récupération de l'ID de l'univers choisi
$idUnivers = $_SESSION['idUnivers'];

// Récupération des données de l'univers choisi
$stmt = $pdo->prepare('SELECT * FROM univers WHERE id = :universe_id');
$stmt->execute(['universe_id' => $idUnivers]);
$univers = $stmt->fetch();


// Récupération des galaxies de l'univers choisi
$stmt = $pdo->prepare('SELECT * FROM galaxie WHERE idUnivers = :universe_id');
$stmt->execute(['universe_id' => $idUnivers]);
$galaxies = $stmt->fetchAll();
// On inverse le sens du tableau des galaxies pour avoir un hordre croissant
$galaxies = array_reverse($galaxies);


// Récupération des systèmes solaires de la première galaxie
$stmt = $pdo->prepare('SELECT * FROM systeme_solaire WHERE idGalaxie = :galaxie_id');
$stmt->execute(['galaxie_id' => $galaxies[0]['id']]);
$systemesSolaire = $stmt->fetchAll();
$systemesSolaire = array_reverse($systemesSolaire);

// Lors que l'on change de galaxie, on récupère les systèmes solaires de la galaxie choisie
if (isset($_GET['galaxie'])) {
    $systChoisie = $pdo->prepare('SELECT * FROM systeme_solaire WHERE idGalaxie = :galaxy_id');
    $systChoisie->execute(['galaxy_id' => $_GET['galaxie']]);
    $systemesSolaire = $systChoisie->fetchAll();
}


$planetes = []; // Initialisation de $planetes en tant que tableau vide
// Lors qu'on change de système solaire, on récupère les planètes du système solaire choisi
if (isset($_GET['systeme-solaire'])) {
    $planeteChoisie = $pdo->prepare('SELECT * FROM planete WHERE idSysteme = :systeme_solaire_id');
    $planeteChoisie->execute(['systeme_solaire_id' => $_GET['systeme-solaire']]);
    $planetes = $planeteChoisie->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <title>Page de gestion des planetes</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>

<body>
    <h2> vous êtes dans l'univers <?php echo $univers['nom']; ?></h1>
        <h2>Sélectionner une galaxie et un système solaire</h1>
            <form method="get">
                <div>
                    <label>Galaxie :</label>
                    <select name="galaxie" onchange="this.form.submit()">
                        <?php foreach ($galaxies as $galaxie) : ?>
                            <option value="<?php echo $galaxie['id']; ?>"><?php echo $galaxie['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label>Système solaire :</label>
                    <select name="systeme-solaire" onchange="this.form.submit()">
                        <?php foreach ($systemesSolaire as $systeme) : ?>
                            <option value="<?php echo $systeme['id']; ?>"><?php echo $systeme['nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Planète :</label>
                    <?php foreach ($planetes as $planete) : ?>
                        <button type="submit" value="<?php echo $planete['id']; ?>"><?php echo $planete['nom']; ?></button>
                    <?php endforeach; ?>
                </div>
            </form>
            <script src="./../js/controllers/applicationGalaxie.js" type="module"></script>
</body>

</html>