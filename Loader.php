<?php
$G_sRacine = dirname(__FILE__);

$G_sPathParam = "$G_sRacine/model/param.php";
if (file_exists($G_sPathParam)) {
    require_once($G_sPathParam);
}

if(str_ends_with($_SERVER['DOCUMENT_ROOT'], "/"))
    $uri = substr_replace($_SERVER['DOCUMENT_ROOT'], "", -1);
else
    $uri = $_SERVER['DOCUMENT_ROOT'];

$G_sPath   = str_replace($uri, "", str_replace("\\", "/", dirname(__FILE__)));

// Active Register Session Per Navigator
session_start();