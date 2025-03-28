<?php
function route($path, $method, callable $function = null) 
{
    global $routes;

    if ($function === null) 
    {
        //header("Content-Type: application/json");
        // Si aucune fonction n'est fournie, on cherche à exécuter une route
        foreach ($routes as $route) 
        {
            if (matchRoute($path, $route['path']) && $route['method'] === $method) 
            {
                $params = getRouteParams($path, $route['path']);
                call_user_func_array($route['function'], $params);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
        return;
    } else 
    {
        // Enregistrer une nouvelle route
        $routes[] = [
            'path' => $path,
            'method' => $method,
            'function' => $function
        ];
    }
}

function matchRoute($requestedPath, $registeredPath) 
{
    $requestedParts = explode('/', trim($requestedPath, '/'));
    $registeredParts = explode('/', trim($registeredPath, '/'));

    if (count($requestedParts) !== count($registeredParts))
        return false; // Les chemins doivent avoir le même nombre de segments

    if(str_contains($registeredParts[count($registeredParts)-1], "{}") && str_contains($requestedParts[count($requestedParts)-1], ","))
    {
        $listRequested  = explode(",", string: ($requestedParts[count($requestedParts)-1]));
        $listRegistered = explode(",", ($registeredParts[count($registeredParts)-1]));
        
        array_splice($requestedParts, count($registeredParts)-1, 1);
        $req = array_merge($requestedParts, $listRequested);
        $requestedParts = $req;

        array_splice($registeredParts, count($registeredParts)-1, 1);
        $reg = array_merge($registeredParts, $listRegistered);
        $registeredParts = $reg;
    }
    
    foreach ($registeredParts as $key => $part) 
    {        
        if ($part !== "{}" && $part !== $requestedParts[$key])
            return false;
    }

    return true;
}

function getRouteParams($requestedPath, $registeredPath) 
{
    $requestedParts = explode('/', trim($requestedPath, '/'));
    $registeredParts = explode('/', trim($registeredPath, '/'));
    
    if(str_contains($registeredParts[count($registeredParts)-1], "{}") && str_contains($requestedParts[count($requestedParts)-1], ","))
    {
        $listRequested  = explode(",", string: ($requestedParts[count($requestedParts)-1]));
        $listRegistered = explode(",", ($registeredParts[count($registeredParts)-1]));
        
        array_splice($requestedParts, count($requestedParts)-1, 1);
        $req = array_merge($requestedParts, $listRequested);
        $requestedParts = $req;

        array_splice($registeredParts, count($registeredParts)-1, 1);
        $reg = array_merge($registeredParts, $listRegistered);
        $registeredParts = $reg;
    }

    $params = [];
    foreach ($registeredParts as $key => $part) 
    {
        if ($part === '{}')
            $params[] = $requestedParts[$key] ?? null;
    }
    return $params;
}
