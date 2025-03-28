<?php
global $G_sRacine, $G_sTitle;
if ( $_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $G_sRacine="..";
}

$G_sTitle .= ' - 404';
include "$G_sRacine/view/header.php";