<?php

try {
  // Connexion à MySQL avec PDO
  $pdo = new PDO('mysql:host=localhost;', 'root', '');

  // Option pour afficher les erreurs SQL
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Création de la base de données
  $pdo->exec('CREATE DATABASE IF NOT EXISTS `galactique2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;');

  // Utilisation de la base de données
  $pdo->exec('USE `galactique2`;');

  // Création des tables
  $pdo->exec('
    CREATE TABLE joueur (
      id INT NOT NULL AUTO_INCREMENT,
      email VARCHAR(255) NOT NULL,
      nom VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      PRIMARY KEY (id)
    );
  ');

  $pdo->exec('
  CREATE TABLE univers (
    id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
  CREATE TABLE galaxie (
    id INT NOT NULL AUTO_INCREMENT,
    idUnivers INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idUnivers) REFERENCES univers(id)
  );
');

  $pdo->exec('
  CREATE TABLE systeme_solaire (
    id INT NOT NULL AUTO_INCREMENT,
    idGalaxie INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idGalaxie) REFERENCES galaxie(id)
  );
');

  $pdo->exec('
  CREATE TABLE planete (
    id INT NOT NULL AUTO_INCREMENT,
    idSysteme INT NOT NULL,
    position INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    pseudo VARCHAR(255) NOT NULL DEFAULT "Planète sans nom",
    idType INT NOT NULL,
    idJoueur INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idSysteme) REFERENCES systeme_solaire(id),
    FOREIGN KEY (idJoueur) REFERENCES joueur(id)
  );
');

  $pdo->exec('
  CREATE TABLE ressource (
    id INT NOT NULL AUTO_INCREMENT,
    idUnivers INT NOT NULL,
    idJoueur INT NOT NULL,
    stockMétal INT NOT NULL DEFAULT 0,
    stockEnergie INT NOT NULL DEFAULT 0,
    stockDeutérium INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (idUnivers) REFERENCES univers(id),
    FOREIGN KEY (idJoueur) REFERENCES joueur(id)
  );
');

  $pdo->exec('
  CREATE TABLE recherche (
    id INT NOT NULL AUTO_INCREMENT,
    typeRecherche VARCHAR(255) NOT NULL,
    niveau INT NOT NULL,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
  CREATE TABLE chantier_spatial (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
  CREATE TABLE prod_deuterium (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
  CREATE TABLE prod_nanit (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
  CREATE TABLE prod_metal (
    id INT NOT NULL AUTO_INCREMENT,
    niveau INT NOT NULL,
    production INT NOT NULL,
    PRIMARY KEY (id)
  );
');

  $pdo->exec('
CREATE TABLE prod_fusion (
  id INT NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  production INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE statue_flotte (
  id INT NOT NULL AUTO_INCREMENT,
  statutFlotte VARCHAR(255) NOT NULL,
  description TEXT,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE flotte (
  id INT NOT NULL AUTO_INCREMENT,
  idPlanete INT NOT NULL,
  idStatFlotte INT NOT NULL,
  nb_croiseur INT NOT NULL,
  nb_transporteur INT NOT NULL,
  nb_coloniseur INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete),
  FOREIGN KEY (idStatFlotte) REFERENCES statue_flotte(idStatFlotte)
);
');
  //numeroFlotte INT NOT NULL, optionelle si on veut pouvoir nommer les flottes

  $pdo->exec('
CREATE TABLE vaisseau (
  id INT NOT NULL AUTO_INCREMENT,
  typeVaisseau VARCHAR(255) NOT NULL,
  flotte INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE cout (
  id INT NOT NULL AUTO_INCREMENT,
  structureType VARCHAR(255) NOT NULL,
  coutMetal INT NOT NULL,
  coutEnergie INT NOT NULL,
  coutDeutérium INT NOT NULL,
  augmentationParNiveau INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE contrainte_recherche (
  id INT NOT NULL AUTO_INCREMENT,
  idRecherche INT NOT NULL,
  idRechercheSouhaiter INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idRecherche) REFERENCES recherche(idRecherche),
  FOREIGN KEY (idRechercheSouhaiter) REFERENCES recherche(idRecherche)
);
');

  $pdo->exec('
CREATE TABLE statu_fil (
  id INT NOT NULL AUTO_INCREMENT,
  étatStatu VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE fil_de_recherche (
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
');

  $pdo->exec('
CREATE TABLE fil_de_construction_infrastructure (
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
');

  $pdo->exec('
CREATE TABLE fil_de_construction_vaisseau (
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
');

  $pdo->exec('
CREATE TABLE defense (
  id INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  niveau INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE type_planete (
  id INT NOT NULL AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE parametre_planete (
  id INT NOT NULL AUTO_INCREMENT,
  position INT NOT NULL,
  taille INT NOT NULL,
  bonusSolaire INT NOT NULL,
  bonusMetaliques INT NOT NULL,
  bonusAquatique INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE joueur_univers (
  id INT NOT NULL AUTO_INCREMENT,
  idJoueur INT NOT NULL,
  idUnivers INT NOT NULL,
  status VARCHAR(255),
  PRIMARY KEY (id),
  FOREIGN KEY (idJoueur) REFERENCES joueur(idJoueur),
  FOREIGN KEY (idUnivers) REFERENCES univers(idUnivers)
);
');

  $pdo->exec('
CREATE TABLE type_vaisseau (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE type_recherche (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE rapport_de_combat (
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
');

  $pdo->exec('
CREATE TABLE infrastructure (
  id INT NOT NULL AUTO_INCREMENT,
  idPlanete INT NOT NULL,
  niveauMinier INT NOT NULL,
  chantierSpatial INT NOT NULL,
  LaboratoireRecherche INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (idPlanete) REFERENCES planete(idPlanete)
);
');

  $pdo->exec('
CREATE TABLE artillerie_laser (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE cannon_ions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('
CREATE TABLE bouclier (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  niveau INT NOT NULL,
  cout_solaire INT NOT NULL,
  cout_metal INT NOT NULL,
  cout_eau INT NOT NULL,
  contrainte_type_de_recherche INT NOT NULL,
  contrainte_niveau_de_recherche INT NOT NULL,
  PRIMARY KEY (id)
);
');

  $pdo->exec('INSERT INTO type_planete (type) VALUES ("Type 1"), ("Type 2"), ("Type 3")');

  $pdo->exec(
    '
INSERT INTO cout (structureType, coutMetal, coutEnergie, coutDeuterium, augmentationParNiveau) VALUES
    (\'RECHERCHE ENERGIE\', 0, 0, 100, 2),
    (\'RECHERCHE LASER\', 0, 0, 300, 0),
    (\'RECHERCHE IONS\', 0, 0, 500, 0),
    (\'RECHERCHE BOUCLIER\', 0, 0, 1000, 5),
    (\'ARMEMENT\', 500, 0, 200, 3),
    (\'CHASSEUR\', 3000, 0, 500, 0),
    (\'CROISEUR\', 20000, 0, 5000, 0),
    (\'TRANSPORTEUR\', 6000, 0, 1500, 0),
    (\'VAISSEAU DE COLONISATION\', 10000, 0, 10000, 0),
    (\'LABORATOIRE DE RECHERCHE\', 1000, 500, 0, 0),
    (\'CHANTIER SPATIAL\', 500, 500, 0, 0),
    (\'USINE DE NANITES\', 10000, 5000, 0, 0),
    (\'MINE DE METAL\', 100, 10, 0, 0),
    (\'SYNTHETISEUR DE DEUTERIUM\', 200, 50, 0, 0),
    (\'CENTRALE SOLAIRE\', 150, 0, 20, 0),
    (\'CENTRALE A FUSION\', 5000, 2000, 0, 0);'
  );

  $pdo->exec('
INSERT INTO `type_vaisseau` (`nom`) VALUES
(`Chasseur`),
(`Croiseur`),
(`Transporteur`),
(`Coloniseur`);
;');

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
  );

  $pdo->exec('
INSERT INTO contrainte_recherche VALUES (idRecherche, idRecherchesouhaiter)


');
} catch (PDOException $e) {
  echo 'Connexion échouée : ' . $e->getMessage();
}
