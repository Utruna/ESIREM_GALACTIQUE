<?php
if (!isset($_SESSION)) {
    session_start();
}

function prod ($pdo, $_SESSION['idJoueur'], $_SESSION['idUnivers']){
    $query = "UPDATE ressource SET stockMetal = :restMetal, tempsMetal = :tempsMetal, stockEnergie = :restEnergie, tempsEnergie = :tempsEnergie ,stockDeuterium = :restDeuterium, tempsDeuterium = :tempsDeuterium  WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':restMetal', $Metal);
        $stmt->bindValue(':tempsMetal', $date);
        $stmt->bindValue(':restEnergie', $Energie);
        $stmt->bindValue(':tempsEnergie', $date);
        $stmt->bindValue(':restDeuterium', $Deuterium);
        $stmt->bindValue(':tempsDeuterium', $date);
        $stmt->bindValue(':idJoueur', $idJoueur);
        $stmt->bindValue(':idUnivers', $idUnivers);
        $stmt->execute();
}

?>