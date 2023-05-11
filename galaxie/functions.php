<?php
if (!isset($_SESSION)) {
    session_start();
}
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

function getUnivers($pdo, $idUnivers) {
    $stmt = $pdo->prepare('SELECT * FROM univers WHERE id = :universe_id');
    $stmt->execute(['universe_id' => $idUnivers]);
    return $stmt->fetch();
}

function getGalaxies($pdo, $idUnivers) {
    $stmt = $pdo->prepare('SELECT * FROM galaxie WHERE idUnivers = :universe_id');
    $stmt->execute(['universe_id' => $idUnivers]);
    return array_reverse($stmt->fetchAll());
}

function getSystemesSolaire($pdo, $galaxieId) {
    $stmt = $pdo->prepare('SELECT * FROM systeme_solaire WHERE idGalaxie = :galaxie_id');
    $stmt->execute(['galaxie_id' => $galaxieId]);
    return array_reverse($stmt->fetchAll());
}

function getPlanetes($pdo, $systemeSolaireId) {
    $stmt = $pdo->prepare('SELECT * FROM planete WHERE idSysteme = :systeme_solaire_id');
    $stmt->execute(['systeme_solaire_id' => $systemeSolaireId]);
    $planetes = $stmt->fetchAll();
    usort($planetes, function ($a, $b) {
        return $a['nom'] <=> $b['nom'];
    });
    return $planetes;
}

function getJoueur($pdo, $joueurId) {
    $stmt = $pdo->prepare('SELECT nom FROM joueur WHERE id = :idJoueur');
    $stmt->execute(['idJoueur' => $joueurId]);
    $joueur = $stmt->fetch();
    if (!$joueur) {
        $joueur['nom'] = 'vide';
    }
    return $joueur;
}

function getTypePlanete($pdo, $typeId) {
    $stmt = $pdo->prepare('SELECT nom FROM type_planete WHERE id = :idType');
    $stmt->execute(['idType' => $typeId]);
    return $stmt->fetch();
}
?>
