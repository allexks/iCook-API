<?php

require_once "classes/models/Dish.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$term = $URI_PATH[3] ?? "";
if (!$term) {
    $response = new Response(400, "No search term specfified.");
    $response->send();
}

$term = rawurldecode($term);

$dishes = Dish::fetchAllMatching($term, $db);
if ($dishes === false) {
    $dishes = [];
}

$response = new DataResponse("Search results ready.", $dishes);
$response->send();

?>
