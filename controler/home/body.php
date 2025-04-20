<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $G_sRacine = "..";
}

include "$G_sRacine/view/section_head.php";
include "$G_sRacine/view/menu.php";
include "$G_sRacine/view/section_apropos.php";
include "$G_sRacine/view/contact.php";
include "$G_sRacine/view/footer.php";

