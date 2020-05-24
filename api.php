<?php 

error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

require_once "vendor/autoload.php";
require_once "config.inc.php";
require_once "api_error_codes.inc.php";
require_once "api_parsers.php";

// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => FONTEFIER_MYSQL_DATABASE,
    'server' =>        FONTEFIER_MYSQL_HOST,
    'username' =>      FONTEFIER_MYSQL_USER,
    'password' =>      FONTEFIER_MYSQL_PASSWORD,
    'charset' =>       'utf8mb4',
	'collation' =>     'utf8mb4_general_ci',
]);

$request = $_SERVER['REQUEST_URI'];
$request = explode('/', trim($request, '/'));
$endpoint = strstr($request[1], '?', true);
if ($endpoint === false)
{
    $endpoint = $request[1];
}

/* Parsing a request */

$responce["http_code"] = 200;

switch ($endpoint) {
    case "font":
        $responce_result = api_parse_font($request, $responce, $database);
        break;

    case "fonts":
        $responce_result = api_parse_fonts($request, $responce, $database);
        break;

    case "glyph":
        $responce_result = api_parse_glyph($request, $responce, $database);
        break;
    
    case "":
        api_empty_endpoint($responce);
        break;
    
    default:
        api_unsupported_endpoint($endpoint, $responce);
        break;
}

/* Sending a responce */

if ($responce["ok"] === true)
{
    unset($responce["description"]);
    unset($responce["error_code"]);
    $responce["result"] = $responce_result;
}

http_response_code($responce["http_code"]);
unset($responce["http_code"]);

echo json_encode($responce);

?>