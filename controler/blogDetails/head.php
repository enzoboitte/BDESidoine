<?php
global $G_sRacine, $G_sTitle;

if ($_SERVER["SCRIPT_FILENAME"] == __FILE__){
    $G_sRacine = "..";
}

include "$G_sRacine/view/header.php";
?>

<!--CSS de la page blog -->
<link rel="stylesheet" href="assets/css/blogDetails.css">