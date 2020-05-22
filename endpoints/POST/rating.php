<?php

require_once "classes/models/Rating.class.php";
require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";

$token_data = Token::validate();

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$data = json_decode(file_get_contents("php://input"));

$rating = new Rating($db);
$rating->user_id = $token_data->id;
$rating->recipe_id = $data->recipe_id ?? 0;

if (empty($rating->recipe_id)) {
    $response = new Response(400, "Please specify all needed info.");
    $response->send();
}

if ($rating->fetchForUser() === false) {
    $response = new Response(500, "Unable to submit rating. Please try again later.");
    $response->send();
}

$rating->rating = $data->rating ?? 0;
$rating->comment = $data->comment ?? null;

if (isset($rating->id) && !empty($rating->id)) {
    // There already exists a rating by this user, let's update it.
    if (!$rating->update()) {
        $response = new Response(500, "Unable to update rating. Please try again later.");
        $response->send();
    }
} else {
    // No previous rating => create a new one.
    if (!$rating->create()) {
        $response = new Response(500, "Unable to create rating. Please try again later.");
        $response->send();
    }
}

$response = new Response(200, "Successfully submitted new rating.");
$response->send();

?>
