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
		ifFirstConnexion($joueur['id'], $idUnivers);
		header('Location:../galaxie/galaxie.php');
		exit();
	} else {
		// Le joueur n'a pas été trouvé ou le mot de passe est incorrect
		header('Location:index.php');
		$_SESSION['erreur'] = 'Email ou mot de passe incorrect';
	}
}

function ifFirstConnexion($idPlayer, $idUnivers){
	$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
	var_dump($idUnivers);

	//Je récupère toutes les planètes existante dans cette univers qui appartiennent à mon joueur
	$requestSearchAllPlaneteAsUnivers =
		"SELECT COUNT(id) AS total FROM `planete` WHERE idJoueur= :idPlayer AND idSystemeSolaire IN 
            (SELECT id FROM `systeme_solaire` WHERE idGalaxie IN 
             ( SELECT id FROM `galaxie` WHERE idUnivers = :id) );";

	$rep = $pdo->prepare($requestSearchAllPlaneteAsUnivers);
	$rep->execute(['id' => $idUnivers, 'idPlayer' => $idPlayer]);
	$result = $rep->fetchAll();

	//Je tire un nombre aleatoire pour fournir une nouvelle plannete a notre nouveau joueur
	$NbPlanetes = intval($result[0]['total']);

	var_dump($NbPlanetes);
	var_dump($NbPlanetes == 0);

	if ($NbPlanetes == 0)
		firstConnexion($idPlayer, $idUnivers);
}

function firstConnexion($idPlayer, $idUnivers)
{	
try{
	$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
	$nomPlanete = attributionPlanete($pdo, $idUnivers, $idPlayer);
	tableRessourceJoueur($pdo, $idUnivers, $idPlayer);
	ajoutJoueurALaListeDesJoueurDeLUnivers($pdo, $idUnivers, $idPlayer);
	initTemps($pdo, $idPlayer);
	$_SESSION['good_alert'] = 'on t\'a trouvé une planète ! c\'est la planete ' . $nomPlanete . ' !';
}
catch (Exception $e){
	$_SESSION['bad_alert'] = 'Erreur lors de la 1ère connection';
}
}
function attributionPlanete($pdo, $idUnivers, $idPlayer){
	
	var_dump($idUnivers);

	//Je récupère toutes les planètes existante dans cette univers qui n'appartiennent à personne
	$requestSearchAllPlaneteAsUnivers =
		" SELECT id FROM `planete` WHERE idjoueur=0 AND idSystemeSolaire IN 
            (SELECT id FROM `systeme_solaire` WHERE idGalaxie IN 
             ( SELECT id FROM `galaxie` WHERE idUnivers = :id) );";

	$rep = $pdo->prepare($requestSearchAllPlaneteAsUnivers);
	$rep->execute(['id' => $idUnivers]);
	$planetes = $rep->fetchAll();

	//Je tire un nombre aleatoire pour fournir une nouvelle plannete a notre nouveau joueur
	$idPlanete = $planetes[rand(0, sizeof($planetes))][0];
	var_dump($idPlanete);
	var_dump($idPlayer);
	//Attribution de la planete
	$rep = $pdo->prepare('UPDATE planete SET idJoueur = :idJoueur WHERE id = :idPlanete');
	$rep->execute(['idPlanete' => $idPlanete, 'idJoueur' => $idPlayer]);

	//Attribution d'une table infrastructure au joueur
	$stmt = $pdo->prepare('INSERT INTO infrastructure 
	(
		idPlanete, 
		niveauLabo, 
		niveauChantierSpatial, 
		niveauUsineNanite, 
		niveauUsineMetal, 
		niveauCentraleSolaire, 
		niveauCentraleFusion, 
		niveauArtillerieLaser, 
		niveauCannonIons, 
		niveauBouclier, 
		niveauTechEnergie, 
		niveauTechLaser, 
		niveauTechIons, 
		niveauTechBouclier, 
		niveauTechArmement
	) 
	VALUES (:idPlanete, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0, 0, 0, 0)');
	$stmt->execute(['idPlanete' => $idPlanete]);

	//Attribution d'une table flotte a la planete
	$stmt = $pdo->prepare('INSERT INTO flotte (idPlanete) VALUES (:idPlanete)');
	$stmt->execute(['idPlanete' => $idPlanete]);


	//Récupération nom de la planete attribuer pour l'alerte
	$rep = $pdo->prepare('SELECT nom FROM planete WHERE id = :idPlanete');
	$rep->execute(['idPlanete' => $idPlanete]);
	$nomPlanete = $rep->fetch();
	return $nomPlanete['nom'];
}


function tableRessourceJoueur($pdo, $idUnivers, $idPlayer){
	//Attribution d'une table ressources au joueur
	$query = "INSERT INTO ressource (idUnivers, idJoueur, stockMetal, stockEnergie, stockDeuterium) VALUES (:idUnivers, :idJoueur, 30000, 6500, 6000)";
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':idUnivers', $idUnivers);
	$stmt->bindValue(':idJoueur', $idPlayer);
	$stmt->execute();
}

function ajoutJoueurALaListeDesJoueurDeLUnivers($pdo, $idUnivers, $idPlayer){
	//Ajout du joueur dans la liste des joueur de l'univers
	$query = "INSERT INTO joueur_univers (idUnivers, idJoueur) VALUES (:idUnivers, :idJoueur)";
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':idUnivers', $idUnivers);
	$stmt->bindValue(':idJoueur', $idPlayer);
	$stmt->execute();
}

function initTemps($pdo, $idJoueur){
	$maintenant = time();
	$query = "INSERT INTO production (idJoueur, temps) VALUES (:idJoueur, :maintenant)";
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':idJoueur', $idJoueur);
	$stmt->bindValue(':maintenant', $maintenant);
	$stmt->execute();
}
?>