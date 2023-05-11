<?php
if (!isset($_SESSION)) {
	session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
// Vérification si le formulaire de création de compte a été soumis

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nom'])) {
    // Récupération des données du formulaire de création de compte
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $passwordHacher = hash("sha512", $password);

    // Vérification si unicité du login (email)
    $rep = $pdo->prepare('SELECT * FROM joueur WHERE email = :email');
    $rep->execute(['email' => $email]);
    $joueur = $rep->fetch();

    if ($joueur) {
        // renvoie sur une page d'erreur hTTP 409
        header("Location:/erreur409.php");
    }
    else {
        // Envoie dans la bdd
        $envoi = $pdo->prepare('INSERT INTO joueur (nom, email, password) VALUES (:nom, :email, :password)');
        $envoi->execute(['nom' => $nom, 'email' => $email, 'password' => $passwordHacher]);
        $joueur = $envoi->fetch();
        header('Location:index.php');
    }

}
?>