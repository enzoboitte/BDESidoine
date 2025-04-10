<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $G_sRacine = "..";
}

include "$G_sRacine/view/top_page.php";
include "$G_sRacine/model/Event.php";
/*$event = new CEvents();
$events = $event->getEvents();

var_dump($events);

// recuperation des events plus tard
$event = new CEvents(true);
$events = $event->getEvents();

var_dump($events);*/

include "$G_sRacine/view/event.php";

include "$G_sRacine/view/bottom_page.php";

// modification de la couleur du background
$G_sCss .= "body {
    background-color: #dadada !important;
}";