<?php
global $G_sYear;
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $G_sRacine = "../..";
}

require_once "$G_sRacine/model/Permission.php";
global $G_sPath, $G_lPermission;
if(!(new CRegle)->F_bIsAutorise(ERegle::READ_MEMBER, $G_lPermission))
{
    header("Location: $G_sPath/dashboard/");
    exit;
}

include "$G_sRacine/view/dashboard/membres.php";