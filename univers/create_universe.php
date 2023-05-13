<?php 
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
if(isset($_POST['create_universe'])) {
    $nomUnivers = $_POST['nom_univers'];

    // Vérification si le nom de l'univers existe déjà
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM univers WHERE nom = :nom_univers');
    $stmt->execute(['nom_univers' => $nomUnivers]);
    $count = $stmt->fetchColumn();

    if ($count > 0) { // Si le nom de l'univers existe déjà
        $error_message = 'Le nom de l\'univers existe déjà.'; 
        include './../acceuille/index.php'; // Redirige vers la page index
        exit;
    }
    else { // Si le nom de l'univers n'existe pas
        
        // Création de l'univers
        $pdo->exec('INSERT INTO univers (nom) VALUES ("'.$nomUnivers.'")');
        $universeId = $pdo->lastInsertId();

        // Création des 5 galaxies de l'univers
        for ($i = 1; $i <= 5; $i++) {
            $pdo->exec('INSERT INTO galaxie (idUnivers, nom, numero) VALUES ('.$universeId.', "Galaxie '.$i.'", '.$i.')');
            $galaxyId = $pdo->lastInsertId();

            // Création des 10 systèmes solaires de la galaxie
            for ($j = 1; $j <= 10; $j++) {
                $systemeNom = "Système solaire ".$j." de la Galaxie ".$i;  // Nom du système solaire 
                $systemeNumero = $j;   
                $pdo->exec('INSERT INTO systeme_solaire (idGalaxie, nom, numero) VALUES ('.$galaxyId.', "'.$systemeNom.'", '.$systemeNumero.')');
                $systemId = $pdo->lastInsertId();
                
                // Création des planètes
                $positions = range(0, 9); // Crée un tableau de 0 à 9
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
    
    header('Location: ./../acceuille/index.php');
}
