<?php

date_default_timezone_set('Europe/Sofia');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "classes/util/Database.class.php";
require_once "classes/views/Response.class.php";

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

$endpoints_dir = "endpoints";
$endpoints_filename = "$endpoints_dir/$endpoint.php";

if (file_exists($endpoints_filename)) {
    include $endpoints_filename;
} else {
    $response = new Response(404, "Requested endpoint is not available.");
    $response->send();
}

?>
