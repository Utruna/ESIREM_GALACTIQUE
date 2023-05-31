<?php
if (!isset($_SESSION)) {
  session_start();
}

echo "Début d'installation </br>";
try {
  // Connexion à MySQL avec PDO
  $pdo = new PDO('mysql:host=localhost;', 'root', '');
  $_SESSION['pdo'] = $pdo;
  // Option pour afficher les erreurs SQL
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Création de la base de données
  $pdo->exec('CREATE DATABASE IF NOT EXISTS `galactique2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');
  echo "Création Base de données </br>";

  // Utilisation de la base de données
  $pdo->exec('USE `galactique2`;');
  echo "Connexion à la BDD </br>";
  echo "</br>";
  echo "Début création tables </br>";

  // Création des tables
  $pdo->exec('
    CREATE TABLE IF NOT EXISTS joueur (
      id INT NOT NULL AUTO_INCREMENT,
      email VARCHAR(255) NOT NULL,
      nom VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      PRIMARY KEY (id)
    );
  ');echo "- Joueur créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS univers (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (id)
  );
');echo "- Univers créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS galaxie (
    id INT NOT NULL AUTO_INCREMENT,
    idUnivers INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idUnivers) REFERENCES univers(id)
  );
');echo "- Galaxie créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS systeme_solaire (
    id INT NOT NULL AUTO_INCREMENT,
    idGalaxie INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idGalaxie) REFERENCES galaxie(id)
  );
');echo "- Système solaire créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS planete (
    id INT NOT NULL AUTO_INCREMENT,
    idSystemeSolaire INT NOT NULL,
    position INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    pseudo VARCHAR(255) NOT NULL DEFAULT "Planète sans nom",
    idType INT NOT NULL,
    idJoueur INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idSystemeSolaire) REFERENCES systeme_solaire(id),
    FOREIGN KEY (idJoueur) REFERENCES joueur(id)
  );
');echo "- Planete créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS ressource (
    id INT NOT NULL AUTO_INCREMENT,
    idUnivers INT NOT NULL,
    idJoueur INT NOT NULL,
    stockMetal INT NOT NULL DEFAULT 0,
    stockEnergie INT NOT NULL DEFAULT 0,
    stockDeuterium INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (idUnivers) REFERENCES univers(id),
    FOREIGN KEY (idJoueur) REFERENCES joueur(id)
  );
');echo "- Ressource créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS recherche (
    id INT NOT NULL AUTO_INCREMENT,
    typeRecherche VARCHAR(255) NOT NULL,
    niveau INT NOT NULL,
    PRIMARY KEY (id)
  );
');echo "- Recherche créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS chantier_spatial (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');echo "- Chantier spatial créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS prod_deuterium (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');echo "- Prod Deuterium créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS prod_nanit (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');echo "- Prod Nanit créé </br>";

  $pdo->exec('
  CREATE TABLE IF NOT EXISTS prod_metal (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');echo "- Prod Metal créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS prod_fusion (
  id INT NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  production INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Prod Fusion créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS statue_flotte (
  id INT NOT NULL AUTO_INCREMENT,
  statutFlotte VARCHAR(255) NOT NULL,
  description TEXT,
  PRIMARY KEY (id)
);
');echo "- Statu flotte créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS flotte (
  id INT NOT NULL AUTO_INCREMENT,
  idPlanete INT NOT NULL,
  idStatFlotte INT NOT NULL,
  nb_chasseur INT NOT NULL,
  nb_croiseur INT NOT NULL,
  nb_transporteur INT NOT NULL,
  nb_coloniseur INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete),
  FOREIGN KEY (idStatFlotte) REFERENCES statue_flotte(idStatFlotte)
);
');echo "- Flotte créé </br>";
  //numeroFlotte INT NOT NULL, optionelle si on veut pouvoir nommer les flottes

  $pdo->exec('
CREATE TABLE IF NOT EXISTS vaisseau (
  id INT NOT NULL AUTO_INCREMENT,
  typeVaisseau VARCHAR(255) NOT NULL,
  flotte INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Vaisseau créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS cout (
  id INT NOT NULL AUTO_INCREMENT,
  structureType VARCHAR(255) NOT NULL,
  coutMetal INT NOT NULL,
  coutEnergie INT NOT NULL,
  coutDeuterium INT NOT NULL,
  augmentationParNiveau INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Cout créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS contrainte_recherche (
  id INT NOT NULL AUTO_INCREMENT,
  idRecherche INT NOT NULL,
  idRechercheSouhaiter INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idRecherche) REFERENCES recherche(idRecherche),
  FOREIGN KEY (idRechercheSouhaiter) REFERENCES recherche(idRecherche)
);
');echo "- Contrainte recherche créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS statu_fil (
  id INT NOT NULL AUTO_INCREMENT,
  étatStatu VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Statu fil créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS fil_de_recherche (
  id INT NOT NULL AUTO_INCREMENT,
  idStatu INT NOT NULL,
  idPlanete INT NOT NULL,
  niveau INT NOT NULL,
  ordreDePriorite INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idStatu) REFERENCES statu_fil(idStatFil),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');echo "- Fil de Recherche créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS fil_de_construction_infrastructure (
  id INT NOT NULL AUTO_INCREMENT,
  idStatu INT NOT NULL,
  idPlanete INT NOT NULL,
  niveau INT NOT NULL,
  ordreDePriorite INT NOT NULL,
  nombre INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idStatu) REFERENCES statu_fil(idStatFil),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');echo "- File de Construction infrastructure créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS fil_de_construction_vaisseau (
  id INT NOT NULL AUTO_INCREMENT,
  idStatu INT NOT NULL,
  idPlanete INT NOT NULL,
  nombre INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  ordreDePriorite INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idStatu) REFERENCES statu_fil(idStatFil),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');echo "- Fil de Construction Vaisseau créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS defense (
  id INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  niveau INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Defense créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS type_planete (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Type Planete créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS parametre_planete (
  id INT NOT NULL AUTO_INCREMENT,
  position INT NOT NULL,
  taille INT NOT NULL,
  bonusSolaire INT NOT NULL,
  bonusMetaliques INT NOT NULL,
  bonusAquatique INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Parametre planete créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS joueur_univers (
  id INT NOT NULL AUTO_INCREMENT,
  idJoueur INT NOT NULL,
  idUnivers INT NOT NULL,
  status VARCHAR(255),
  PRIMARY KEY (id),
  FOREIGN KEY (idJoueur) REFERENCES joueur(idJoueur),
  FOREIGN KEY (idUnivers) REFERENCES univers(idUnivers)
);
');echo "- Joueur univers créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS type_vaisseau (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Type Vaisseau créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS type_recherche (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Type Recherche créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS rapport_de_combat (
  id INT NOT NULL AUTO_INCREMENT,
  idFlotteATK_Debut INT NOT NULL,
  idFlotteATK_Fin INT NOT NULL,
  idFlotteDEF_Debut INT NOT NULL,
  idFlotteDEF_Fin INT NOT NULL,
  idPlanete INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idFlotteATK_Debut) REFERENCES flotte(idFlotte),
  FOREIGN KEY (idFlotteATK_Fin) REFERENCES flotte(idFlotte),
  FOREIGN KEY (idFlotteDEF_Debut) REFERENCES flotte(idFlotte),
  FOREIGN KEY (idFlotteDEF_Fin) REFERENCES flotte(idFlotte),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');echo "- Rapport de combat créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS infrastructure (
  id INT NOT NULL AUTO_INCREMENT,
  idPlanete INT NOT NULL,
  niveauLabo INT NOT NULL,
  niveauChantierSpatial INT NOT NULL,
  niveauUsineNanite INT NOT NULL,
  niveauUsineMetal INT NOT NULL,
  niveauSynthetiseurDeut INT NOT NULL,
  niveauCentraleSolaire INT NOT NULL,
  niveauCentraleFusion INT NOT NULL,
  niveauArtillerieLaser INT NOT NULL,
  niveauCannonIons INT NOT NULL,
  niveauBouclier INT NOT NULL,
  niveauTechEnergie INT NOT NULL,
  niveauTechLaser INT NOT NULL,
  niveauTechIons INT NOT NULL,
  niveauTechBouclier INT NOT NULL,
  niveauTechArmement INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');echo "- Infrastructure créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS artillerie_laser (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Artilleri laser créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS cannon_ions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Cannon ions créé </br>";

  $pdo->exec('
CREATE TABLE IF NOT EXISTS bouclier (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');echo "- Bouclier créé </br>";


    echo "</br></br>";

    echo "- Initialisation table </br>";

  $pdo->exec('INSERT INTO type_planete (nom) VALUES ("banale"), ("aquatique"), ("tellurique")');
  echo "- Type planete implémanteé </br>";

  $pdo->exec('
INSERT INTO cout (structureType, coutMetal, coutEnergie, coutDeuterium, augmentationParNiveau) VALUES
  (\'recherche_energie\', 0, 0, 100, 2),
  (\'recherche_laser\', 0, 0, 300, 0),
  (\'recherche_ions\', 0, 0, 500, 0),
  (\'recherche_bouclier\', 0, 0, 1000, 5),
  (\'armement\', 500, 0, 200, 3),
  (\'chasseur\', 3000, 0, 500, 0),
  (\'croiseur\', 20000, 0, 5000, 0),
  (\'transporteur\', 6000, 0, 1500, 0),
  (\'vaisseau_de_colonisation\', 10000, 0, 10000, 0),
  (\'laboratoire_de_recherche\', 1000, 500, 0, 0),
  (\'chantier_spatial\', 500, 500, 0, 0),
  (\'usine_de_nanites\', 10000, 5000, 0, 0),
  (\'mine_de_metal\', 100, 10, 0, 0),
  (\'synthetiseur_de_deuterium\', 200, 50, 0, 0),
  (\'centrale_solaire\', 150, 0, 20, 0),
  (\'centrale_a_fusion\', 5000, 2000, 0, 0);
  (\'artillerie_laser\', 1500, 0, 300, 0),
  (\'cannon_a_ions\', 5000, 0, 1000, 0),
  (\'bouclier\', 1000, 1000, 5000, 0);
');echo "- Cout implémanteé </br>";

  $pdo->exec('INSERT INTO type_vaisseau(nom) VALUES 
                        ("Chasseur"),
                        ("Croiseur"),
                        ("Transporteur"),
                        ("Coloniseur")
                        ;');echo "- Type vaisseau implémanteé </br>";

  $pdo->exec(
    "
    INSERT INTO `recherche` (typeRecherche, niveau) VALUES
    ('energie', 0),
    ('energie', 1),
    ('energie', 2),
    ('energie', 3),
    ('energie', 4),
    ('energie', 5),
    ('energie', 6),
    ('energie', 7),
    ('energie', 8),
    ('energie', 9),
    ('energie', 10),
    ('laser', 0),
    ('laser', 1),
    ('laser', 2),
    ('laser', 3),
    ('laser', 4),
    ('laser', 5),
    ('laser', 6),
    ('laser', 7),
    ('laser', 8),
    ('laser', 9),
    ('ions', 0),
    ('ions', 1),
    ('ions', 2),
    ('ions', 3),
    ('ions', 4),
    ('ions', 5),
    ('ions', 6),
    ('ions', 7),
    ('ions', 8),
    ('ions', 9),
    ('ions', 10),
    ('bouclier', 0),
    ('bouclier', 1),
    ('bouclier', 2),
    ('bouclier', 3),
    ('bouclier', 4),
    ('bouclier', 5),
    ('bouclier', 6),
    ('bouclier', 7),
    ('bouclier', 8),
    ('bouclier', 9),
    ('bouclier', 10),
    ('armement', 0),
    ('armement', 1),
    ('armement', 2),
    ('armement', 3),
    ('armement', 4),
    ('armement', 5),
    ('armement', 6),
    ('armement', 7),
    ('armement', 8),
    ('armement', 9),
    ('armement', 10);
    "
  );echo "- Recherche implémanteé </br>";

  $pdo->exec("
INSERT INTO contrainte_recherche (idRecherche, idRechercheSouhaiter)
SELECT r1.id, r2.id
FROM recherche r1, recherche r2
WHERE r1.typeRecherche = 'laser' AND r1.niveau = 5
AND r2.typeRecherche = 'ions' AND r2.niveau = 0;
");echo "- Contrainte recherche implémanteé </br>";

  $pdo->exec("
INSERT INTO contrainte_recherche (idRecherche, idRechercheSouhaiter)
SELECT r1.id, r2.id
FROM recherche r1, recherche r2
WHERE r1.typeRecherche = 'energie' AND r1.niveau = 5
AND r2.typeRecherche = 'laser' AND r2.niveau = 0;
");echo "- Contrainte recherche energie & laser planete implémanteé </br>";

  $pdo->exec("
  INSERT INTO contrainte_recherche (idRecherche, idRechercheSouhaiter)
  SELECT r1.id, r2.id
  FROM recherche r1, recherche r2
  WHERE r1.typeRecherche = 'energie' AND r1.niveau = 8
  AND r2.typeRecherche = 'bouclier' AND r2.niveau = 0;
");echo "- Contrainte recherche energie &bouclier planete implémanteé </br>";

  $pdo->exec("
INSERT INTO contrainte_recherche (idRecherche, idRechercheSouhaiter)
SELECT r1.id, r2.id
FROM recherche r1, recherche r2
WHERE r1.typeRecherche = 'ions' AND r1.niveau = 2
AND r2.typeRecherche = 'bouclier' AND r2.niveau = 0;
");echo "- Contrainte recherche ions & boulier planete implémanteé </br>";


  $pdo->exec("
INSERT INTO statue_flotte (statutFlotte, description)
VALUES
  ('Attend', 'Flotte en attente'),
  ('Attaque', 'Flotte en phase d\'attaque'),
  ('Détruite', 'Flotte détruite');
");echo "- Statue flotte implémanteé </br>";


$_SESSION['good_alert'] = "La base de donnée a été initialisé avec succès";
header('Location: ./acceuille/index.php');
exit;
} catch (PDOException $e) {
  echo 'Connexion échouée : ' . $e->getMessage();
  var_dump($e->getMessage());
}
