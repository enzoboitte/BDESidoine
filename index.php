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

// route pour le dashboard
route('/dashboard/{}', 'GET', function ($link) 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";

    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }
    //(new CAccount())->F_vDieSession();
    F_vRequest("dashboard/$link");
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

# /v1/account/logout
route('/v1/account/logout', 'GET', function () 
{
    global $G_sRacine, $G_sPath;
    include_once "$G_sRacine/model/Account.php";

    (new CAccount())->F_vDieSession();

    header("Location: $G_sPath/");
});

# /v1/event/add/{title},{date},{hour},{type}
route('/v1/event/add/', 'POST', function () 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    //$l_sTitle, $l_sDate, $l_sHour, $l_sType dans le body de la requête {"title":"fghdfgh","date":"2025-04-07","hour":"15:00","type":"event"}
    $data = json_decode(file_get_contents('php://input'), true);
    $l_sTitle = $data['title'];
    $l_sDate = $data['date'];
    $l_sHour = $data['hour'];
    $l_sType = $data['type'];

    $res = (new CEvents())->F_bAddEvent($l_sTitle, $l_sDate." ".$l_sHour, "", $l_sType);
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/rm/{id}
route('/v1/event/rm/{id}', 'POST', function ($l_sId) 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    $res = (new CEvents())->F_bRmEvent($l_sId);
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/update/{id},{title},{date},{hour},{type}
route('/v1/event/update/{},{},{},{},{}', 'POST', function ($l_sId, $l_sTitle, $l_sDate, $l_sHour, $l_sType) 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    $res = (new CEvents())->F_bUpdateEvent($l_sId, $l_sTitle, $l_sDate, $l_sHour, $l_sType);
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/get/{id}
route('/v1/event/get/{id}', 'POST', function ($l_sId) 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    $e = new CEvents();
    $e->F_vLoadAllEvents();
    $res = $e->F_lGetEvent($l_sId);

    $events = [];

    foreach ($res as $event) 
    {
        $events[] = $event->toJson();
    }
    echo json_encode([
            "code" => $events,
            "msg" => "ok"
        ]);
});

# /v1/event/get/all
route('/v1/event/get/all', 'POST', function () 
{
    global $G_sRacine,$G_sPath;
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    $e = new CEvents();
    $e->F_vLoadAllEvents();
    $res = $e->getEvents();

    $events = [];

    foreach ($res as $event) 
    {
        $events[] = $event->toJson();
    }

    echo json_encode([
            "code" => $events,
            "msg" => "ok"
        ]);
});













$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestUri = str_replace(str_replace("\\", "/", str_ireplace(str_ireplace("/", "\\", $_SERVER['DOCUMENT_ROOT']), "", dirname(__FILE__))), "", $requestUri);
$requestUri = str_replace("//", "/", $requestUri);

route($requestUri, $requestMethod);