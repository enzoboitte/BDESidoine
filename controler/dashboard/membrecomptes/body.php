<?php
global $G_sYear;
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $G_sRacine = "../..";
}

include "$G_sRacine/view/dashboard/membres.php";