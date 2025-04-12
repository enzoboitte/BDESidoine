<?php

include_once "$G_sRacine/model/PDOModel.php";

class CMembers  extends CPDOModel
{
    public function getMembersByYear($year) {
        $pdo = $this->F_cGetDB();
        $stmt = $pdo->prepare("
            SELECT m.*, r.libelle AS role
            FROM membre m
            LEFT JOIN role r ON m.idRo = r.idRo
            WHERE m.annee = :annee
        ");
        $stmt->bindParam(':annee', $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}