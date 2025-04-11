<?php
include_once "$G_sRacine/model/PDOModel.php";
include_once "$G_sRacine/model/Role.php";

class CAccount extends CPDOModel
{
    public function F_iLogin(string $l_sIdentifier, string $l_sPasswd): int
    {
        $l_cSql  = $this->F_cGetDB()->prepare("CALL procLogin(:idnom, :idpren, :passwd);");

        [$l_sIdnom, $l_sIdpren] = explode(".", $l_sIdentifier);

        $l_cSql->execute(["idnom" => $l_sIdnom, "idpren" => $l_sIdpren, "passwd" => sha1($l_sPasswd)]);
        $l_lRes  = $l_cSql->fetch(PDO::FETCH_ASSOC);
        $l_iCode = $l_lRes["code"];

        //die(var_dump($l_lRes));

        if($l_iCode == 0)
            $_SESSION["tmpkey"] = $l_lRes["tmpKey"];

        return (int) $l_iCode;
    }

    // recuperation des infos client
    public function F_lGetInfo(): array
    {
        if(!$this->F_bIsConnect())
            return [];
        $l_cSql  = $this->F_cGetDB()->prepare("SELECT m.`idM`, m.`nom`, m.`prenom`, m.`mail`, m.`tel` FROM `compte` c INNER JOIN `membre` m ON m.`idM` = c.`idM` WHERE `tmpkey` = :tmpkey LIMIT 0,1;");

        $l_cSql->execute(["tmpkey" => $_SESSION["tmpkey"]]);

        return $l_cSql->fetch(PDO::FETCH_ASSOC);
    }

    public static function F_sGetRealPasswd(string $l_sPasswd): string
    {
        return sha1(sha1($l_sPasswd)."bdesid_service");
    }

    public function F_vDieSession()
    {
        $l_cSql  = $this->F_cGetDB()->prepare("UPDATE `compte` SET `tmpkey` = '' WHERE `tmpkey` = :tmpkey;");

        $l_cSql->execute(["tmpkey" => $_SESSION["tmpkey"]]);
    }

    public function F_bIsConnect(): bool
    {
        if(!isset($_SESSION["tmpkey"]))
            return false;

        $l_cSql  = $this->F_cGetDB()->prepare("SELECT COUNT(*) as 'total' FROM `compte` WHERE `tmpkey` = :tmpkey;");
        $l_cSql->execute(["tmpkey" => $_SESSION["tmpkey"]]);

        return $l_cSql->fetch(PDO::FETCH_ASSOC)["total"] == 1;
    }
 
    // recuperation du roles de l'utilisateur en fonction de la date SELECT `idM`, `idRo`, `idA` FROM `nommer` WHERE 1
    function F_cGetRole(string $l_cDate): CRole|null
    {
        if(!isset($_SESSION["tmpkey"]))
            return null;

        // recuperation de l'idM de l'utilisateur
        $l_iId = $this->F_lGetInfo();
        if($l_iId == null)
            return null;

        $l_iId = $l_iId["idM"];

        // recuperation de l'idRo de l'utilisateur
        $l_cSql  = $this->F_cGetDB()->prepare("SELECT ro.`idRo`, ro.`libelle` FROM `nommer` n INNER JOIN role ro ON ro.`idRo` = n.`idRo` WHERE n.`idM` = :idM AND n.`idA` = :date;");

        $l_cSql->execute(["idM" => $l_iId, "date" => $l_cDate]);

        $l_lRes = $l_cSql->fetch(PDO::FETCH_ASSOC);

        return new CRole($l_lRes["idRo"], $l_lRes["libelle"]);
    }

    // recuperation des permissions de l'utilisateur en fonction de la date et de l'idRo
    function F_lGetPermission(string $l_cDate): array
    {
        if(!isset($_SESSION["tmpkey"]))
            return [];

        $l_iIdRo = $this->F_cGetRole($l_cDate);
        if($l_iIdRo == null)
            return [];

        $l_iIdRo = $l_iIdRo->getIdRo();

        // recuperation des permissions de l'utilisateur
        $l_cSql  = $this->F_cGetDB()->prepare("SELECT r.`idR` FROM `contient` c INNER JOIN `regle` r ON r.`idR` = c.`idR` WHERE c.`idRo` = :idRo;");

        $l_cSql->execute(["idRo" => $l_iIdRo]);

        return $l_cSql->fetchAll(PDO::FETCH_ASSOC);
    }
}