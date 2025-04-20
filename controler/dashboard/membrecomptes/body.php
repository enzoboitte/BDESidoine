<?php
global $G_sYear;
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $G_sRacine = "../..";
}

require_once "$G_sRacine/model/Members.php";
require_once "$G_sRacine/model/Role.php";


$CMembers = new CMembers();
$membres = $CMembers->getMembersByYear($G_sYear);

include "$G_sRacine/view/dashboard/membres.php";