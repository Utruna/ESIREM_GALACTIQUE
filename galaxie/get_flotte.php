<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
function getFlotte($pdo, $planeteId) {
    $stmt = $pdo->prepare('SELECT * FROM flotte WHERE idPlanete = :idPlanete');
    $stmt->execute(['idPlanete' => $planeteId]);
    $flotte = $stmt->fetchAll();
    return $flotte;
    header('Location:./galaxie.php');
}

?>