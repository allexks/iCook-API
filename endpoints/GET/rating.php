<?php

require_once "classes/models/Rating.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$rating_id = $URI_PATH[3] ?? 0;
if (!$rating_id) {
    $response = new Response(400, "No rating id specfified.");
    $response->send();
}

$rating = new Rating($db);
$rating->id = $rating_id;

if (!$rating->fetch()) {
    $response = new Response(404, "No rating for the given id exists.");
    $response->send();
}

$response = new DataResponse("Rating fetched.", $rating);
$response->send();

?>
