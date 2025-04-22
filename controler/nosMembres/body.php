<?php
global $G_sRacine;
require_once "$G_sRacine/model/Members.php";

$year = $_GET['annee'] ?? '2024-2025';
$CMembers = new CMembers();
$members = $CMembers->F_lMembersByYear($year);

//$G_sBackBtn=true;
//include "$G_sRacine/view/section_head.php";
//include "$G_sRacine/view/menu.php";
include "$G_sRacine/view/top_page.php";
include "$G_sRacine/view/nosMembres.php";
include "$G_sRacine/view/footer.php";