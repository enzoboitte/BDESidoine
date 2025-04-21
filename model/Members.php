<?php
include_once "$G_sRacine/model/PDOModel.php";
require_once "$G_sRacine/model/Role.php";

class CMember 
{
    private $idM;
    private $nom;
    private $prenom;
    private $mail;
    private $tel;
    private $_image;
    private $role;

    public function __construct($idM, $nom, $prenom, $mail, $tel, $_image, $role = null) {
        $this->idM = $idM;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mail = $mail;
        $this->tel = $tel;
        $this->_image = $_image;
        $this->role = $role;
    }

    public function getIdM() {
        return $this->idM;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getTel() {
        return $this->tel;
    }

    public function getImage() {
        return $this->_image;
    }

    public function getRole() {
        return $this->role;
    }
}

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
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $members = array_map(function($m) {
            return new CMember($m['idM'], $m['nom'], $m['prenom'], $m['mail'], $m['tel'], $m['image'], new CRole($m['idRo'], $m['role']));
        }, $res);
        return $members;
    }

    public function getMembreById($id) {
        $stmt = $this->F_cGetDB()->prepare("
            SELECT m.*, r.libelle AS role, r.idRo
            FROM membre m
            INNER JOIN nommer n ON m.idM = n.idM
            INNER JOIN role r ON n.idRo = r.idRo
            WHERE m.idM = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return new CMember($res['idM'], $res['nom'], $res['prenom'], $res['mail'], $res['tel'], $res['image'], new CRole($res['idRo'], $res['role']));
        }
        return null;
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