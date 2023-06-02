<?php
if (!isset($_SESSION)) {
    session_start();
}
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

// Récupération des info de l'univers correpondant à l'id de l'univers de la session en cours
function getUnivers($pdo, $idUnivers) {
    $stmt = $pdo->prepare('SELECT * FROM univers WHERE id = :universe_id');
    $stmt->execute(['universe_id' => $idUnivers]);
    return $stmt->fetch();
}

// Récupération des galaxies d'un univers
function getGalaxies($pdo, $idUnivers) {
    $stmt = $pdo->prepare('SELECT * FROM galaxie WHERE idUnivers = :universe_id');
    $stmt->execute(['universe_id' => $idUnivers]);
    return $stmt->fetchAll();
}

// Récupération des systèmes solaires d'une galaxie
function getSystemesSolaire($pdo, $galaxieId) {
    $stmt = $pdo->prepare('SELECT * FROM systeme_solaire WHERE idGalaxie = :galaxie_id');
    $stmt->execute(['galaxie_id' => $galaxieId]);
    return $stmt->fetchAll();
}

// Récupération des planètes d'un système solaire
function getPlanetes($pdo, $systemeSolaireId) {
    $stmt = $pdo->prepare('SELECT * FROM planete WHERE idSystemeSolaire = :systeme_solaire_id');
    $stmt->execute(['systeme_solaire_id' => $systemeSolaireId]);
    $planetes = $stmt->fetchAll();
    usort($planetes, function ($a, $b) {
        return $a['nom'] <=> $b['nom'];
    });
    return $planetes;
}

// Récupération du joueur a qui appartient une planete donner
function getJoueur($pdo, $joueurId) {
    $stmt = $pdo->prepare('SELECT nom FROM joueur WHERE id = :idJoueur');
    $stmt->execute(['idJoueur' => $joueurId]);
    $joueur = $stmt->fetch();
    if (!$joueur) {
        $joueur['nom'] = 'vide';
    }
    return $joueur;
}

// Récupération type de planete
function getTypePlanete($pdo, $typeId) {
    $stmt = $pdo->prepare('SELECT nom FROM type_planete WHERE id = :idType');
    $stmt->execute(['idType' => $typeId]);
    $result = $stmt->fetch();
    return $result['nom'];
}


function getPlaneteJoueurAtaque($pdo, $idJoueur, $idUnivers) {
    // récupération des galaxie de l'univers actuel 
    $stmt = $pdo->prepare('SELECT * FROM galaxie WHERE idUnivers = :idUnivers');
    $stmt->execute(['idUnivers' => $idUnivers]);
    $galaxies = $stmt->fetchAll();
    // récupération des systèmes solaires de chaque galaxie
    $systemesSolaire = [];
    foreach ($galaxies as $galaxie) {
        $stmt = $pdo->prepare('SELECT * FROM systeme_solaire WHERE idGalaxie = :idGalaxie');
        $stmt->execute(['idGalaxie' => $galaxie['id']]);
        $systemesSolaire = array_merge($systemesSolaire, $stmt->fetchAll());
    }
    // récupération des planètes de chaque système solaire
    $planetes = [];
    foreach ($systemesSolaire as $systemeSolaire) {
        $stmt = $pdo->prepare('SELECT * FROM planete WHERE idSystemeSolaire = :idSystemeSolaire AND idJoueur = :idJoueur');
        $stmt->execute(['idSystemeSolaire' => $systemeSolaire['id'], 'idJoueur' => $idJoueur]);
        $planetes = array_merge($planetes, $stmt->fetchAll());
    }
    return $planetes;
}


// Afin de référencer les planetes du joueur dans la page galaxie.php on liste les planetes du joueur
function getPlaneteJoueur($pdo, $idJoueur, $idUnivers) {
    $stmt = $pdo->prepare('SELECT p.*, ss.nom AS nomSystemeSolaire, g.nom AS nomGalaxie, u.nom AS nomUnivers
                           FROM planete p
                           INNER JOIN systeme_solaire ss ON p.idSystemeSolaire = ss.id
                           INNER JOIN galaxie g ON ss.idGalaxie = g.id
                           INNER JOIN univers u ON g.idUnivers = u.id
                           WHERE p.idJoueur = :idJoueur AND u.id = :idUnivers');
    $stmt->execute(['idJoueur' => $idJoueur, 'idUnivers' => $idUnivers]);
    $planetes = $stmt->fetchAll();
    return $planetes;
}
