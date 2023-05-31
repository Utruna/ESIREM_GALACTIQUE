<?php
if (!isset($_SESSION)) {
    session_start();
}
// Connexion à MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
try {
    // Modification du nom de la planete en fonction du nom entré dans le formulaire
    $nouveauNom = $_POST['nomPlanete'];
    $idPlanete = $_SESSION['idPlanete'];
    $query = "UPDATE planete SET nom = :nouveauNom WHERE id = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':nouveauNom', $nouveauNom);
    $stmt->bindValue(':idPlanete', $idPlanete);
    $stmt->execute();
    $_SESSION['good_alert'] = "Nom de la planète modifié";
    header('Location: ./manager.php');
}
catch (Exception $e) {
    $_SESSION['bad_alert'] = "Problème lors de la modification du nom de la planète";
    header('Location: ./manager.php');
}

