<?php

include_once "$G_sRacine/model/PDOModel.php";

class CMembers extends CPDOModel
{
    public function getMembersByYear($libelleAnnee) {
        $pdo = $this->F_cGetDB();
        $stmt = $pdo->prepare("
            SELECT m.*, r.libelle AS role
            FROM membre m
            INNER JOIN nommer n ON m.idM = n.idM
            INNER JOIN annee a ON a.idA = n.idA
            INNER JOIN role r ON n.idRo = r.idRo
            WHERE a.libelle = :annee
        ");
        $stmt->bindParam(':annee', $libelleAnnee);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}