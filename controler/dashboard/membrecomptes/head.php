<?php
global $G_sRacine, $G_sTitle; 
if ( $_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $G_sRacine="..";
}

include "$G_sRacine/view/header.php";