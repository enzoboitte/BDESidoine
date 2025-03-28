<?php
define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

include_once "./Loader.php";
require_once "$G_sRacine/model/Routes.php";

// route pour le dashboard
route('/{}', 'GET', function ($link) 
{
    global $G_sRacine;
    include_once "$G_sRacine/model/Account.php";
    //(new CAccount())->F_vDieSession();
    F_vRequest($link);
});

function F_vRequest(string $link)
{
    header("Content-Type: text/html");
    global $G_sRacine, $G_sAction, $G_sLayout;
    
    $G_sAction  = isset($link) && $link != "" ? $link : "default";
    include_once "$G_sRacine/controler/ctlMain.php";
    include_once "$G_sRacine/view/$G_sLayout";
    if($G_sLayout != "layout.php")
        $G_sLayout = "layout.php";
}

// route pour les requêtes API

# /v1/account/login/{username},{password}
route('/v1/account/login/{},{}', 'GET', function ($l_sIdentifier, $l_sPasswd) 
{
    global $G_sRacine;
    include_once "$G_sRacine/model/Account.php";

    $res = (new CAccount())->F_iLogin($l_sIdentifier, $l_sPasswd);
    $msg = "";

    switch($res)
    {
        case 0: $msg = $_SESSION["tmpkey"]; break;
        case 1: $msg = "Identifiant incorrect."; break;
        case 2: $msg = "Mot de passe incorrect."; break;
        default: $msg = "internal error."; break;
    }

    echo json_encode([
            "code" => $res,
            "msg" => $msg
        ]);
});
















$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestUri = str_replace(str_replace("\\", "/", str_ireplace(str_ireplace("/", "\\", $_SERVER['DOCUMENT_ROOT']), "", dirname(__FILE__))), "", $requestUri);
$requestUri = str_replace("//", "/", $requestUri);

route($requestUri, $requestMethod);