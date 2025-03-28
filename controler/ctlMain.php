<?php
global $G_sAction;

function F_sCtlPrincipal($l_sAction) {
    global $G_sRacine, $G_lActions;
    
    
    if(array_key_exists($l_sAction, $G_lActions))
    {
        $l_sFichier = $G_lActions[$l_sAction];
        if(!file_exists("$G_sRacine/controler/$l_sFichier/body.php"))
            $l_sFichier = $G_lActions["405"];
    }
    else
        $l_sFichier = $G_lActions["404"];

    return $l_sFichier;
}

$G_sCss     = "";
$G_lJsPathH  = []; // H == Header
$G_lJsPathB  = []; // B == Body
$G_sFichier = F_sCtlPrincipal($G_sAction);

ob_start();
include "$G_sRacine/controler/$G_sFichier/head.php";
$G_sHeader = ob_get_clean();

ob_start();
include "$G_sRacine/controler/$G_sFichier/body.php";
$G_sBody = ob_get_clean();


