<?php
// affichage d'erreur
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// recuperation de l'année scolaire avec en mois et jour 01-01
function F_cGetDate()
{
    $l_cDate = new DateTime();
    $l_iAnnee = $l_cDate->format("Y");
    $l_iMois  = $l_cDate->format("m");
    $l_iJour  = $l_cDate->format("d");

    if($l_iMois < 9 || ($l_iMois == 9 && $l_iJour < 1))
        $l_iAnnee--;

    return new DateTime($l_iAnnee."-01-01");
}

$G_sYear = F_cGetDate()->format("Y");
$G_sDate = F_cGetDate()->format("Y-m-d");
$G_lPermission = [];

// si connecté, on recupere les role de l'utilisateur
if(isset($_SESSION["tmpkey"]))
{
    require_once "$G_sRacine/model/Account.php";
    require_once "$G_sRacine/model/Role.php";

    $G_lPermission = (new CAccount())->F_lGetPermission($G_sDate);
}