<?php 

error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

require_once "vendor/autoload.php";
require_once "config.inc.php";
require_once "api_handlers.php";
require_once "auth.php";
require_once "db.php";

/* Getting a request */

$request = $_SERVER['REQUEST_URI'];
$request = explode('/', trim($request, '/'));
$endpoint = strstr($request[1], '?', true);
if ($endpoint === false)
{
    $endpoint = $request[1];
}

/* Connecting to database */

databaseInit();

/* Parsing a request */

$responce["http_code"] = 200;

switch ($endpoint) {
    case "font":
        $responce_result = api_handle_font($request, $responce);
        break;

    case "fonts":
        $responce_result = api_handle_fonts($request, $responce);
        break;

    case "glyph":
        $responce_result = api_handle_glyph($request, $responce);
        break;
    
    case "":
        api_empty_endpoint($responce);
        break;
    
    default:
        api_unsupported_endpoint($endpoint, $responce);
        break;
}

/* Sending a responce */

if ($responce["http_code"] === 200)
{
    unset($responce["description"]);
    unset($responce["error_code"]);
    $responce["result"] = $responce_result;
}

http_response_code($responce["http_code"]);
unset($responce["http_code"]);

echo json_encode($responce);

?>