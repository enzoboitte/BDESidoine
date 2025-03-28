<?php
include "$G_sRacine/model/PDOModel.php";

class CAccount extends CPDOModel
{
    public function F_iLogin(string $l_sIdentifier, string $l_sPasswd): int
    {
        $l_cSql  = $this->F_cGetDB()->prepare("CALL procLogin(:id, :passwd);");

        $l_cSql->execute(["id" => $l_sIdentifier, "passwd" => sha1($l_sPasswd)]);
        $l_lRes  = $l_cSql->fetch(PDO::FETCH_ASSOC);
        $l_iCode = $l_lRes["code"];

        //die(var_dump($l_lRes));

        if($l_iCode == 0)
            $_SESSION["tmpkey"] = $l_lRes["tmpKey"];

        return (int) $l_iCode;
    }

    public static function F_sGetRealPasswd(string $l_sPasswd): string
    {
        return sha1(sha1($l_sPasswd)."f1_service");
    }

    public function F_vDieSession()
    {
        $l_cSql  = $this->F_cGetDB()->prepare("UPDATE `admin` SET `tmpkey` = '' WHERE `tmpkey` = :tmpkey;");

        $l_cSql->execute(["tmpkey" => $_SESSION["tmpkey"]]);
    }

    public function F_bIsConnect(): bool
    {
        if(!isset($_SESSION["tmpkey"]))
            return false;

        $l_cSql  = $this->F_cGetDB()->prepare("SELECT COUNT(*) as 'total' FROM `admin` WHERE `tmpkey` = :tmpkey;");
        $l_cSql->execute(["tmpkey" => $_SESSION["tmpkey"]]);

        return $l_cSql->fetch(PDO::FETCH_ASSOC)["total"] == 1;
    }
}