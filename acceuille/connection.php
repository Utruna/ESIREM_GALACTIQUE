<?php
if (!isset($_SESSION)) {
	session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
	if (isset($_POST['email']) && isset($_POST['password'])) {
		// Récupération des données du formulaire
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password = hash("sha512", $password);
		$idUnivers = $_POST['idUnivers'];
		$_SESSION['idUnivers'] = $idUnivers;

		// Vérification si l'email existe dans la base de données
		$rep = $pdo->prepare('SELECT * FROM joueur WHERE email = :email');
		$rep->execute(['email' => $email]);
		$joueur = $rep->fetch();

		if ($joueur && $password == $joueur['password']) {
			// Le joueur a été trouvé et le mot de passe est correct
			$_SESSION['idJoueur'] = $joueur['id'];
			header('Location:../galaxie/galaxie.php');
			exit();
		} else {
			// Le joueur n'a pas été trouvé ou le mot de passe est incorrect
			header('Location:index.php');
			$_SESSION['erreur'] = 'Email ou mot de passe incorrect';
		}
	}
?>