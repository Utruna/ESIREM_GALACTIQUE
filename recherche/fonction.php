<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];

$stmtVaisseaux = $pdo->prepare('SELECT * FROM flotte WHERE idJoueur = :idJoueur');
?>