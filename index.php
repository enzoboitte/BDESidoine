<?php
define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

include_once "./Loader.php";
require_once "$G_sRacine/model/Routes.php";

// route pour le dashboard
route('/{}', 'GET', function ($link) 
{
    F_vRequest($link);
    
});

function F_vRequest(string $link)
{
    header("Content-Type: text/html");
    global $G_sRacine, $G_sAction, $G_sLayout;
    
    $G_sAction  = isset($link) && $link != "" ? $link : "default";
    include "$G_sRacine/controler/ctlMain.php";
    include "$G_sRacine/vue/$G_sLayout";
    if($G_sLayout != "layout.php")
        $G_sLayout = "layout.php";
}
















$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestUri = str_replace(str_replace("\\", "/", str_ireplace(str_ireplace("/", "\\", $_SERVER['DOCUMENT_ROOT']), "", dirname(__FILE__))), "", $requestUri);
$requestUri = str_replace("//", "/", $requestUri);

route($requestUri, $requestMethod);