<?php

date_default_timezone_set('Europe/Sofia');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "classes/util/Database.class.php";
require_once "classes/responses/Response.class.php";

// Database connection

$database = new Database();
$db = $database->getNewConnection();

if (!$db) {
    $response = new Response(500, "Connection to database failed.");
    $response->send();
}

// Router

$URI_PATH = explode("/", $_SERVER["REQUEST_URI"]);
$endpoint = $URI_PATH[2] ?? "";
$method = $_SERVER["REQUEST_METHOD"];

$endpoints_dir = "endpoints";
$endpoint_filename = "$endpoints_dir/$method/$endpoint.php";

if (!file_exists($endpoint_filename)) {
    $response = new Response(404, "Requested endpoint is not available.");
    $response->send();
}

include $endpoint_filename;

?>
