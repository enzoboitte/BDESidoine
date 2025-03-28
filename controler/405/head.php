<?php
global $G_sRacine, $G_sTitle; // Recupère les valeurs des variables dans le fichier param.php
if ( $_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $G_sRacine="..";
}

$G_sTitle .= ' - 405';
include "$G_sRacine/vue/header.php";