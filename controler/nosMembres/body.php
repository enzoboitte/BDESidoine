<?php
require_once "model/CMembers.php";

$year = $_GET['annee'] ?? '2024-2025';
$CMembers = new CMembers();
$members = $CMembers->getMembersByYear($year);

$G_sBackBtn=true;
<<<<<<< HEAD
=======
include "$G_sRacine/view/section_head.php";
>>>>>>> main
include "$G_sRacine/view/menu.php";
include "$G_sRacine/view/nosMembres.php";