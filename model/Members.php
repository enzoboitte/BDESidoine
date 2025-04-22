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

    // toJson
    public function toJson() {
        return [
            'idM' => $this->idM,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'mail' => $this->mail,
            'tel' => $this->tel,
            'image' => $this->_image,
            'role' => $this->role ? $this->role->toJson() : null
        ];
    }

    public function toJsonString() {
        return json_encode($this->toJson());
    }
}

class CMembers extends CPDOModel
{
    public function F_lMembersByYear($libelleAnnee) 
    {
        $pdo = $this->F_cGetDB();
        $stmt = $pdo->prepare("
            SELECT m.*, r.libelle AS role, r.idRo
            FROM membre m
            LEFT OUTER JOIN nommer n ON m.idM = n.idM
            INNER JOIN annee a ON a.idA = n.idA
            LEFT OUTER JOIN role r ON n.idRo = r.idRo
            WHERE a.libelle = :annee
        ");
        $stmt->bindParam(':annee', $libelleAnnee);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $members = array_map(function($m) 
        {
            return new CMember($m['idM'], $m['nom'], $m['prenom'], $m['mail'], $m['tel'], $m['image'], new CRole($m['idRo'], $m['role']));
        }, $res);
        return $members;
    }

    public function F_cMembreById($id) 
    {
        $stmt = $this->F_cGetDB()->prepare("
            SELECT m.*, r.libelle AS role, r.idRo
            FROM membre m
            LEFT OUTER JOIN nommer n ON m.idM = n.idM
            LEFT OUTER JOIN role r ON n.idRo = r.idRo
            WHERE m.idM = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res)
            return new CMember($res['idM'], $res['nom'], $res['prenom'], $res['mail'], $res['tel'], $res['image'], isset($res['idRo']) && isset($res['role']) ? (new CRole($res['idRo'], $res['role'])): null);
        return null;
    }

    public function F_bAddMembre($l_sNom, $l_sPrenom, $l_sMail, $l_sTel, $l_sImage, $l_iIdRo) 
    {
        $data = [
            'nom' => $l_sNom,
            'prenom' => $l_sPrenom,
            'mail' => $l_sMail,
            'tel' => $l_sTel,
            'image' => $l_sImage,
            'idRo' => $l_iIdRo
        ];
        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        try 
        {
            $stmt = $pdo->prepare("INSERT INTO membre (nom, prenom, mail, tel, image, idRo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['image'] == null ? "NULL" : $data["image"], $data['idRo']]);
    
            $idM = $pdo->lastInsertId();
    
            $stmt2 = $pdo->prepare("INSERT INTO nommer (idM, idRo, idA) VALUES (?, ?, (SELECT idA FROM annee WHERE libelle = '2024'))");
            $stmt2->execute([$idM, $data['idRo']]);
    
            $pdo->commit();
        } catch (PDOException $e) 
        {
            $pdo->rollBack();
            echo "Erreur lors de l'ajout du membre : " . $e->getMessage() . "<br>".$l_iIdRo;
            return false;
        }
        return $idM;
    }

    public function F_bUpdateMembre($l_iIdM, $l_sNom, $l_sPrenom, $l_sMail, $l_sTel, $l_sImage, $l_iIdRo) 
    {
        global $G_sRacine;
        $data = [
            'idM' => $l_iIdM,
            'nom' => $l_sNom,
            'prenom' => $l_sPrenom,
            'mail' => $l_sMail,
            'tel' => $l_sTel,
            'image' => $l_sImage,
            'idRo' => $l_iIdRo
        ];

        if (!empty($data['image'])) 
        {
            // récupération de l'image
            $stmt = $this->F_cGetDB()->prepare("SELECT image FROM membre WHERE idM = ? LIMIT 1");
            $stmt->execute([$data['idM']]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            // si l'image existe, on la supprime
            if ($res && !empty($res['image'])) {
                $imagePath = $G_sRacine.$res['image'];
                if (file_exists($imagePath)) {
                    if(!unlink($imagePath))
                    {
                        // erreur lors de la suppression de l'image
                        throw new Exception("Erreur lors de la suppression de l'image : $imagePath");
                    }
                }
            }
        }

        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        try 
        {
            // si l_sImage est vide, on ne met pas à jour l'image
            if (empty($data['image'])) {
                unset($data['image']);
                $stmt = $pdo->prepare("UPDATE membre SET nom = ?, prenom = ?, mail = ?, tel = ? WHERE idM = ?");
                $stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['idM']]);
            } else {
                $stmt = $pdo->prepare("UPDATE membre SET nom = ?, prenom = ?, mail = ?, tel = ?, image = ? WHERE idM = ?");
                $stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['image'], $data['idM']]);
            }
            //$stmt = $pdo->prepare("UPDATE membre SET nom = ?, prenom = ?, mail = ?, tel = ?, _image = ? WHERE idM = ?");
            //$stmt->execute([$data['nom'], $data['prenom'], $data['mail'], $data['tel'], $data['_image'], $data['idM']]);
    
            $stmt2 = $pdo->prepare("UPDATE nommer SET idRo = ? WHERE idM = ? AND idA = (SELECT idA FROM annee WHERE libelle = '2024')");
            $stmt2->execute([$data['idRo'], $data['idM']]);
    
            $pdo->commit();
        } catch (Exception $e) 
        {
            $pdo->rollBack();
            throw $e;
        }
        return true;
    }

    public function F_bDeleteMembre($idM) {
        $pdo = $this->F_cGetDB();
        $pdo->beginTransaction();

        try 
        {
            //$pdo->prepare("DELETE FROM nommer WHERE idM = ?")->execute([$idM]);
            $pdo->prepare("DELETE FROM membre WHERE idM = ?")->execute([$idM]);
    
            $pdo->commit();
        } catch (Exception $e) 
        {
            $pdo->rollBack();
            throw $e;
        }
        return true;
    }
}