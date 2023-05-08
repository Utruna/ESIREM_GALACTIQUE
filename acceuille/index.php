<?php
session_start();
// Si la connexion a la base de donnée ne fonctionne pas on affiche un message d'erreur
try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
  }
catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    echo "merci de faire /install.php pour installer la base de donnée";
    }

if(isset($_POST['connection'])) {
// Vérification si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vérification si l'email existe dans la base de données
        $stmt = $pdo->prepare('SELECT * FROM joueur WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $joueur = $stmt->fetch();

        if ($joueur && $password == $joueur['password']) {
            // Le joueur a été trouvé et le mot de passe est correct
            $_SESSION['idJoueur'] = $joueur['idJoueur'];
            header('Location:../galaxie/galaxi.php');
            exit();
        } else {
            // Le joueur n'a pas été trouvé ou le mot de passe est incorrect
            $error = 'Email ou mot de passe incorrect';
        }
    }
}
// Vérification si le formulaire de création de compte a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nom'])) {
    // Récupération des données du formulaire de création de compte
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];

    // Envoie dans la bdd
    $stmt = $pdo->prepare('INSERT INTO joueur (nom, email, password) VALUES (:nom, :email, :password)');
    $stmt->execute(['nom' => $nom, 'email' => $email, 'password' => $password]);
    $joueur = $stmt->fetch();

}

// Récupération des univers
$stmt = $pdo->query('SELECT nom FROM univers');

if(isset($_POST['create_universe'])) {

    // Création de l'univers
    $pdo->exec('INSERT INTO univers (nom) VALUES ("beta")');
    $universeId = $pdo->lastInsertId();

        // Création des 5 galaxies de l'univers
        for ($i = 1; $i <= 5; $i++) {
            $pdo->exec('INSERT INTO galaxie (idUnivers, nom, numero) VALUES ('.$universeId.', "Galaxie '.$i.'", '.$i.')');
            $galaxyId = $pdo->lastInsertId();

            // Création des 10 systèmes solaires de la galaxie
            for ($j = 1; $j <= 10; $j++) {
                $systemeNom = "Système solaire ".$j." de la Galaxie ".$i; 
                $systemeNumero = $j;   
                $pdo->exec('INSERT INTO systeme_solaire (idGalaxie, nom, numero) VALUES ('.$galaxyId.', "'.$systemeNom.'", '.$systemeNumero.')');
                $systemId = $pdo->lastInsertId();
                
                    // Création des planètes
                    $positions = range(1, 10); // Créé un tableau de 1 à 10
                    shuffle($positions); // Mélange les positions
                    $nbPlanetes = rand(4, 10); // Génère un nombre aléatoire entre 4 et 10 pour le nombre de planètes
                    for ($k = 0; $k < $nbPlanetes; $k++) {
                        $position = $positions[$k]; // Récupère une position aléatoire dans le tableau des positions
                        $idType = rand(1, 3); // Génère un nombre aléatoire entre 1 et 3 pour le type de planète
                        $pdo->exec('INSERT INTO planete (idSysteme, position, nom, idType, idJoueur) VALUES ('.$systemId.', '.$position.', "Planète '.$position.'", '.$idType.', 0)');
                    }
                }
            }
        }        
?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../style/css_index.css" />
</head>
<body>
    <!--<?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>-->
    <video autoplay loop poster="../img/video_image.PNG" id="background-video">
        <source src="vid/video_de_fond.mp4" type="video/mp4">
      </video>
      <audio controls preload="metadata" autoplay loop >
        <source src="../audio/musique_accueil.wav" type="audio/wav">
        <source src="../audio/musique_acceuil.mp3" type="autio/mp3">
      </audio>
    <div id="global">
        <div id="div_inscription">
            <h1>Se connecter</h1>
            <form method="post" id="connexion">
                <input type="email" placeholder="Adresse Email" name="email" required>
                <br>
                <input type="password" placeholder="Mot de passe" name="password" required>
                <br>
                <select name="univers">
                <?php
                // Affichage des univers dans un menu déroulant
                while ($univers = $stmt->fetch()) {
                    echo '<option>' . $univers['nom'] . '</option>';
                }
                ?>
                </select>
                <br>
                <input type="submit" name="create_universe" value="Créer un univers">
                <br>
                <input type="submit" name="connection" value="Se connecter">
                <br>
            </form>
        </div>
        <div id="div_creation">
            <h1>S'inscrire</h1>
            <form method="post" id="creation">
                <input type="text" placeholder="Nom" name="nom" required>
                <br>
                <input type="email" placeholder="Adresse Email" name="email" required>
                <br>
                <input type="password" placeholder="Mot de passe" name="password" required>
                <br>
                <input type="submit" value="Créer le compte">
            </form>
        </div>
    </div>

</body>
</html>