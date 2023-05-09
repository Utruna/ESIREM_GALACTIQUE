<?php 
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
if(isset($_POST['create_universe'])) {

    // Création de l'univers
    $pdo->exec('INSERT INTO univers (nom) VALUES ("gamma")');
    $universeId = $pdo->lastInsertId();

        // Création des 5 galaxies de l'univers
        for ($i = 1; $i <= 5; $i++) {
            $pdo->exec('INSERT INTO galaxie (idUnivers, nom, numero) VALUES ('.$universeId.', "Galaxie '.$i.'", '.$i.')');
            $galaxyId = $pdo->lastInsertId();

            // Création des 10 systèmes solaires de la galaxie
            for ($j = 1; $j <= 10; $j++) {
                $systemeNom = "Système solaire ".$j." de la Galaxie ".$i; 
                $systemeNumero = $j;   
                $pdo->exec('INSERT INTO systeme_solaire (idGalaxie, nom, numero) VALUES ('.$galaxyId.', "'.$systemeNom.'", '.$systemeNumero.')');
                $systemId = $pdo->lastInsertId();
                
                    // Création des planètes
                    $positions = range(1, 10); // Créé un tableau de 1 à 10
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
?>