<?php
require_once "model/CMembers.php";

$year = $_GET['annee'] ?? '2024-2025';
$CMembers = new CMembers();
$members = $CMembers->getMembersByYear($year);

//$G_sBackBtn=true;
//include "$G_sRacine/view/section_head.php";
//include "$G_sRacine/view/menu.php";
include "$G_sRacine/view/top_page.php";
include "$G_sRacine/view/nosMembres.php";
include "$G_sRacine/view/footer.php";