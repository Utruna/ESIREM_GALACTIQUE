<?php
if (!isset($_SESSION)) {
	session_start();
}

include "alert.php";

// Si la connexion a la base de donnée ne fonctionne pas on affiche un message d'erreur
try {
	// Connexion à MySQL avec PDO
	$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
} catch (PDOException $e) {
	echo 'Connexion échouée : ' . $e->getMessage();
	echo "merci de faire /install.php pour installer la base de donnée";
}

// Récupération des univers
$selectuniers = $pdo->query('SELECT * FROM univers');

if (isset($_POST['creation_universe'])) {
	header('Location:../univers/create_universe.php');
}

?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>Page de connexion</title>
	<!-- <link rel="stylesheet" href="../style/css_index.css" /> -->
    <link rel="stylesheet" href="../style/alert.css" />
</head>

<body>
	<?php if (isset($error)) : ?>
		<p><?php echo $error; ?></p>
	<?php endif; ?>
	<!-- <video autoplay loop poster="../img/video_image.PNG" id="background-video">
        <source src="vid/video_de_fond.mp4" type="video/mp4">
      </video>
      <audio controls preload="metadata" autoplay loop >
        <source src="../audio/musique_accueil.wav" type="audio/wav">
        <source src="../audio/musique_acceuil.mp3" type="autio/mp3">
      </audio> -->
	<div id="global">
		<div id="div_se_connecter">
			<h1>Se connecter</h1>
			<form method="post" name="login" action="connection.php">
				<input type="email" placeholder="Adresse Email" name="email" required>
				<br>
				<input type="password" placeholder="Mot de passe" name="password" required>
				<br>
				<select name="idUnivers">
					<?php
					// Affichage des univers dans un menu déroulant
					while ($univers = $selectuniers->fetch()) {
						echo '<option value="' . $univers['id'] . '">' . $univers['nom'] . '</option>';
					}
					?>
				</select>
				<br>
				<button>Connection</button>
				<br>
				<?php
				if (isset($_SESSION['error'])) {
					echo '<p style="color:red">Email ou mot de passe incorrect</p>';
					unset($_SESSION['error']);
				}
				?>
			</form>
			<div id="div_creation">
				<h1>S'inscrire</h1>
				<form method="post" action="creationCompte.php" name="signin">
					<input type="text" placeholder="Nom" name="nom" required />
					<br>
					<input type="email" placeholder="Adresse Email" name="email" required />
					<br>
					<input type="password" placeholder="Mot de passe" name="password" required />
					<br>
					<button>Crée son compte</button>
				</form>
			</div>


			<div id="creation_univers" method="post">
				<form method="post" action="./../univers/create_universe.php">
					<label for="nom_univers">Nom de l'univers :</label>
					<input type="text" name="nom_univers" id="nom_univers" required>
					<input type="submit" name="create_universe" value="Créer un univers">
				</form>
			</div>

		</div>

</body>

</html>