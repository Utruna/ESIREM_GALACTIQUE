
# ESIREM_GALATIQUE
**************************************************************
Avant Lancement du jeu : lancer l'installer a l'adresse http://localhost/ESIREM_GALATIQUE/installer.php
**************************************************************


lors de la création du jeu nous avions penser a plus de table que celles présentes acutuelement mais nous n'avons pas réussi a implémanteé certaines tables.
notament la table des contraintes qui aurait permis de gérer les contraintes de construction et de recherche via la base de données.
nous avions aussi prévue de faire une table par structure pour pouvoir gérer les productions que se soit en ressources ou en vaisseaux. mais nous n'avons pas réussi a implémenter cette table.

certaines table ne sont pas utilsier car nous n'avons pas réussi a les implémenter dans le jeu.
- table fil de construction (infrastrucutre et vaisseaux) qui devais permettre de crée une fille d'attente de construction pour les infrastrucutre et les vaisseaux.
- table fil de recherche qui devais permettre de crée une fille d'attente de recherche.
- table rapport de combat qui devais permettre de stocker les rapports de combat entre les joueurs.
- table statue fil qui devais permettre de géré la temporalité des attaque et des déplacement de flotte.

les principales tables sont :
- table joueur qui permet de stocker les informations sur les joueurs.
- table univers qui permet de stocker les informations sur l'univers et de les distinguer.
- table planete qui permet de stocker les informations sur les planètes. (idem pour les tables galaxie et systeme solaire)
- table infrastructure qui permet de stocker les informations sur les infrastructures de chaque planete indépendament.
- table flotte qui permet de stocker les informations sur les flottes de chaque planete indépendament.
- table ressource
- table cout qui permet de stocker les couts de construction des infrastructures et des vaisseaux.
- table recherche qui permet de stocker les informations sur les recherches.

les principales difficulté rencontré :
- la gestion des ressources et des couts de construction.
- la gestion des recherches.
- la gestion des flottes.
- la mise en place de la page galaxie :
    * mise en place des select pour galaxie et systeme solaire.
    * mise en place du popup pour les attaque.
- mise en place des recherche/construction de vaisseaux/infrastructures.
- attribution et mise en places des table de manière dynamique pour une mielleur utilisation de l'espace de stockage notament a la 1ère connection d'un joueur.

les idée ajouter :
- gestion des flotes séparé de l'attaque car cela ne nous semblais pas logique
- liste des planetes du joueur sur la page galaxie car cela nous semblais plus logique et permet une meilleur navigation.
- les tables relatives au planete et au joueur ne sont attribuer que en cas de besoin pour éviter une base de donnée exisivement lourde.

les chose non fini :
- la gestion des ressources et des construction dans le temps.
    * idée de la mise en place qu'on aurais souhaiter effectuer =>
        nous voulions ajouter une collone sur chaque table avec un horodatage pour pouvoir calculer le temps depuis la dernière mise a jour des tables
        a chaque action effectuer par le joueur nous aurions mis a jour les tables en ajoutant le temps écoulé depuis la dernière mise a jour.
        nous aurions ensuite calculer les ressources produite depuis la dernière mise a jour et les aurions ajouter au ressources du joueur.
        nous aurions aussi pu calculer les construction effectuer depuis la dernière mise a jour et les aurions ajouter au construction en cours du joueur.
        les difficulté que nous aurions rencontré sont que si une upgrade d'une infrastructure de production est effectuer nous aurions du recalculer les ressources 
        produite depuis la dernière mise a jour en prenant en compte le temps de construction de l'upgrade.

- mise en place des attaque qui a pauser énormément de souci.
