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

#############################################
#                 API REST                  #
#############################################

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

#############################################
#                 Events                    #
#############################################
# /v1/event/add/{title},{date},{hour},{type}
route('/v1/event/add/', 'POST', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::CREATE_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    //$l_sTitle, $l_sDate, $l_sHour, $l_sType dans le body de la requête {"title":"fghdfgh","date":"2025-04-07","hour":"15:00","type":"event"}
    /*$data = json_decode(file_get_contents('php://input'), true);
    $l_sTitle = $data['title'];
    $l_sDate = $data['date'];
    $l_sHour = $data['hour'];
    $l_sType = $data['type'];*/
    $requestData = parse_raw_http_request();
    $_POST = $requestData['_POST'];
    if(isset($requestData['_FILES']))
        $_FILES = $requestData['_FILES'];

    // formulaire envoyé en PUT avec enctype="multipart/form-data"
    $l_sTitle = $_POST['title'];
    $l_sDate = $_POST['date'];
    $l_sHour = $_POST['hour'];
    $l_sType = $_POST['type'];

    // recuperer l'image envoyée par le formulaire et l'enregistrer sur le serveur dans $G_sRacine/upload/...
    $uploadDir = $G_sRacine . '/upload';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        //echo "Dossier créé automatiquement : $uploadDir\n";
    }

    $l_sImage = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $l_sImage = $l_sTitle . "_" . $l_sDate . "_" . date("YmdHis") . "." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $l_sImagePath = "$G_sRacine/upload/$l_sImage";
        
        if (copy($_FILES['photo']['tmp_name'], to: $l_sImagePath)) {
            //echo "Image enregistrée : $l_sImagePath\n";
        } else {
            //echo "Échec d'enregistrement de l'image : $l_sImagePath\n";
        }
        
    } else {
        // si pas d'image, on ne change pas l'image existante
        $l_sImagePath = null;
    }

    $res = (new CEvents())->F_bAddEvent(htmlspecialchars($l_sTitle), htmlspecialchars($l_sDate." ".$l_sHour), isset($l_sImagePath) ? htmlspecialchars("/upload/$l_sImage") : null, htmlspecialchars($l_sType));
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/rm/{id}
route('/v1/event/rm/{}', 'DELETE', function ($l_sId) 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::DELETE_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $res = (new CEvents())->F_bRmEvent($l_sId);
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/update/{id},{title},{date},{hour},{type}
route('/v1/event/update/', 'POST', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::UPDATE_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    //$l_sTitle, $l_sDate, $l_sHour, $l_sType dans le body de la requête {"title":"fghdfgh","date":"2025-04-07","hour":"15:00","type":"event"}
    $requestData = parse_raw_http_request();
    $_POST = $requestData['_POST'];
    if(isset($requestData['_FILES']))
        $_FILES = $requestData['_FILES'];

    // formulaire envoyé en PUT avec enctype="multipart/form-data"
    $l_sId = $_POST['id'];
    $l_sTitle = $_POST['title'];
    $l_sDate = $_POST['date'];
    $l_sHour = $_POST['hour'];
    $l_sType = $_POST['type'];

    // recuperer l'image envoyée par le formulaire et l'enregistrer sur le serveur dans $G_sRacine/upload/...
    $uploadDir = $G_sRacine . '/upload';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        //echo "Dossier créé automatiquement : $uploadDir\n";
    }

    $l_sImage = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $l_sImage = $l_sTitle . "_" . $l_sDate . "_" . date("YmdHis") . "." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $l_sImagePath = "$G_sRacine/upload/$l_sImage";
        
        if (copy($_FILES['photo']['tmp_name'], to: $l_sImagePath)) {
            //echo "Image enregistrée : $l_sImagePath\n";
        } else {
            //echo "Échec d'enregistrement de l'image : $l_sImagePath\n";
        }
        
    } else {
        // si pas d'image, on ne change pas l'image existante
        $l_sImagePath = null;
    }

    $res = (new CEvents())->F_bUpdateEvent($l_sId, htmlspecialchars($l_sTitle), htmlspecialchars("$l_sDate $l_sHour"), isset($l_sImagePath) ? htmlspecialchars("/upload/$l_sImage") : null, htmlspecialchars($l_sType));
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});

# /v1/event/get/week/{date}
route('/v1/event/get/week/', 'POST', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::READ_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $l_sDate = $data['date'];

    $e = new CEvents();
    $res = $e->F_lGetEventByDate($l_sDate);
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
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::READ_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
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

# /v1/event/get/{id}
route('/v1/event/get/{}', 'POST', function ($l_sId) 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Event.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::READ_EVENT, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $e = new CEvents();
    $e->F_vLoadAllEvents();
    $res = $e->F_lGetEvent($l_sId);
    echo json_encode([
            "code" => $res->toJson(),
            "msg" => "ok"
        ]);
});

#############################################
#                  Members                  #
#############################################
# /v1/members/get/{id}
route('/v1/members/get/{}', 'POST', function ($l_sId) 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Members.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::READ_MEMBER, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $e = new CMembers();
    $res = $e->F_cMembreById($l_sId);

    if ($res) {
        echo json_encode([
            "code" => $res->toJson(),
            "msg" => "ok"
        ]);
    } else 
    {
        echo json_encode([
            "code" => null,
            "msg" => "Membre non trouvé."
        ]);
    }
});

# /v1/members/get/all
route('/v1/members/get/all', 'POST', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Members.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::READ_MEMBER, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $e = new CMembers();
    $res = $e->F_lMembersByYear(date("Y"));
    $members = [];

    foreach ($res as $member) 
    {
        $members[] = $member->toJson();
    }

    echo json_encode([
            "code" => $members,
            "msg" => "ok"
        ]);
});

# /v1/members/add/{prenom},{nom},{mail},{tel},{role}
route('/v1/members/add/', 'POST', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Members.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::CREATE_MEMBER, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $requestData = parse_raw_http_request();
    $_POST = $requestData['_POST'];
    if(isset($requestData['_FILES']))
        $_FILES = $requestData['_FILES'];

    // formulaire envoyé en POST avec enctype="multipart/form-data"
    $l_sPrenom = $_POST['prenom'];
    $l_sNom = $_POST['nom'];
    $l_sMail = $_POST['mail'];
    $l_sTel = $_POST['tel'];
    $l_iRole = $_POST['role'];

    // recuperer l'image envoyée par le formulaire et l'enregistrer sur le serveur dans $G_sRacine/upload/...
    $uploadDir = $G_sRacine . '/upload';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        //echo "Dossier créé automatiquement : $uploadDir\n";
    }

    $l_sImage = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $l_sImage = $l_sNom . "_" . $l_sPrenom . "_" . date("YmdHis") . "." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $l_sImagePath = "$G_sRacine/upload/$l_sImage";
        
        if (copy($_FILES['photo']['tmp_name'], to: $l_sImagePath)) {
            //echo "Image enregistrée : $l_sImagePath\n";
        } else {
            //echo "Échec d'enregistrement de l'image : $l_sImagePath\n";
        }
        
    } else {
        // si pas d'image, on ne change pas l'image existante
        $l_sImagePath = null;
    }

    (new CMembers())->F_bAddMembre($l_sNom, $l_sPrenom, $l_sMail, $l_sTel, isset($l_sImagePath) ? "/upload/$l_sImage" : null, (int)$l_iRole);

    echo json_encode([
            "code" => 0,
            "msg" => "ok"
        ]);
});

# /v1/members/update/{id},{prenom},{nom},{mail},{tel},{role}
route('/v1/members/update/', 'PUT', function () 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Members.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::UPDATE_MEMBER, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $requestData = parse_raw_http_request();
    $_POST = $requestData['_POST'];
    if(isset($requestData['_FILES']))
        $_FILES = $requestData['_FILES'];

    // formulaire envoyé en PUT avec enctype="multipart/form-data"
    $l_iId = $_POST['id'];
    $l_sPrenom = $_POST['prenom'];
    $l_sNom = $_POST['nom'];
    $l_sMail = $_POST['mail'];
    $l_sTel = $_POST['tel'];
    $l_iRole = $_POST['role'];

    // recuperer l'image envoyée par le formulaire et l'enregistrer sur le serveur dans $G_sRacine/upload/...
    $uploadDir = $G_sRacine . '/upload';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        //echo "Dossier créé automatiquement : $uploadDir\n";
    }

    $l_sImage = "";
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $l_sImage = $l_sNom . "_" . $l_sPrenom . "_" . date("YmdHis") . "." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $l_sImagePath = "$G_sRacine/upload/$l_sImage";
        
        if (copy($_FILES['photo']['tmp_name'], to: $l_sImagePath)) {
            //echo "Image enregistrée : $l_sImagePath\n";
        } else {
            //echo "Échec d'enregistrement de l'image : $l_sImagePath\n";
        }
        
    } else {
        // si pas d'image, on ne change pas l'image existante
        $l_sImagePath = null;
    }

    (new CMembers())->F_bUpdateMembre($l_iId, $l_sNom, $l_sPrenom, $l_sMail, $l_sTel, isset($l_sImagePath) ? "/upload/$l_sImage" : null, (int)$l_iRole);
    
    echo json_encode([
            "code" => 0,
            "msg" => "ok"
        ]);
});

# /v1/members/rm/{id}
route('/v1/members/rm/{}', 'DELETE', function ($l_sId) 
{
    global $G_sRacine,$G_sPath,$G_lPermission;
    include_once "$G_sRacine/model/Permission.php";
    include_once "$G_sRacine/model/Account.php";
    include_once "$G_sRacine/model/Members.php";
    
    if(!(new CAccount())->F_bIsConnect())
    {
        header("Location: $G_sPath/");
        exit;
    }

    if(!(new CRegle)->F_bIsAutorise(ERegle::DELETE_MEMBER, $G_lPermission))
    {
        echo json_encode([
            "code" => "403",
            "msg" => "Accès refusé."
        ]);
        exit;
    }

    $res = (new CMembers())->F_bDeleteMembre($l_sId);
    echo json_encode([
            "code" => $res,
            "msg" => "ok"
        ]);
});




$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestUri = str_replace(str_replace("\\", "/", str_ireplace(str_ireplace("/", "\\", $_SERVER['DOCUMENT_ROOT']), "", dirname(__FILE__))), "", $requestUri);
$requestUri = str_replace("//", "/", $requestUri);

// rajouter le prefixe de l'url si besoin https://<le prefix>.exemple.com/...
//echo  $requestUri;

route($requestUri, $requestMethod);