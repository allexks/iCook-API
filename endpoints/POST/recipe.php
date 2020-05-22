<?php

require_once "classes/models/Recipe.class.php";
require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";

$token_data = Token::validate();

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$data = json_decode(file_get_contents("php://input"));

$recipe = new Recipe($db);
$recipe->user_id = $token_data->id;
$recipe->dish_id = $data->dish_id ?? 0;
$recipe->duration = $data->duration ?? 0;
$recipe->steps = $data->steps ?? "";

if (
    empty($recipe->dish_id) ||
    empty($recipe->steps)
) {
    $response = new Response(400, "Please specify all needed info.");
    $response->send();
}

if (!$recipe->create()) {
    $response = new Response(500, "Unable to create new recipe. Please try again later.");
    $response->send();
}

$response = new Response(200, "Successfully created new recipe.");
$response->send();

?>
