<?php
include_once "$G_sRacine/model/PDOModel.php";

class CMembers extends CPDOModel
{
    public function getMembersByYear($libelleAnnee) {
        $pdo = $this->F_cGetDB();
        $stmt = $pdo->prepare("
            SELECT m.*, r.libelle AS role, r.idRo
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

    public function getMembreById($id) {
        $stmt = $this->F_cGetDB()->prepare("
            SELECT m.*, r.libelle AS role
            FROM membre m
            INNER JOIN nommer n ON m.idM = n.idM
            INNER JOIN role r ON n.idRo = r.idRo
            WHERE m.idM = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMembre($data) {
        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO membre (nom, prenom, mail, tel, _image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['_image']]);

        $idM = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("INSERT INTO nommer (idM, idRo, idA) VALUES (?, ?, (SELECT idA FROM annee WHERE libelle = '2024'))");
        $stmt2->execute([$idM, $data['idRo']]);

        $pdo->commit();
        return $idM;
    }

    public function updateMembre($data) {
        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE membre SET nom = ?, prenom = ?, mail = ?, tel = ?, _image = ? WHERE idM = ?");
        $stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['_image'], $data['idM']]);

        $stmt2 = $pdo->prepare("UPDATE nommer SET idRo = ? WHERE idM = ? AND idA = (SELECT idA FROM annee WHERE libelle = '2024')");
        $stmt2->execute([$data['idRo'], $data['idM']]);

        $pdo->commit();
        return true;
    }

    public function deleteMembre($idM) {
        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        $pdo->prepare("DELETE FROM nommer WHERE idM = ?")->execute([$idM]);
        $pdo->prepare("DELETE FROM membre WHERE idM = ?")->execute([$idM]);

        $pdo->commit();
        return true;
    }
}