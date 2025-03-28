<?php 
//include "/getLoader.php";
include "$G_sRacine/model/PDOModel.php";

class CClassement extends CPDOModel
{
    public function F_lGetEcurie(int $G_iYear): array
    {
        $l_cSql = $this->F_cGetDB()->prepare("SELECT e.`name`, e.`logo`, ce.`nbPoint` FROM `classementec` ce INNER JOIN `ecurie` e ON e.`idEc` = ce.`idEc` WHERE ce.`annee` = :year ORDER BY ce.`nbPoint` desc;");
        $l_cSql->execute(["year" => $G_iYear]);

        return $l_cSql->fetchAll(PDO::FETCH_ASSOC);
    }
}