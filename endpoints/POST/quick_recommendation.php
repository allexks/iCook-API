<?php

require_once "classes/models/Dish.class.php";
require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$token_data = Token::validate();

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$dish = new Dish($db);
$random_dish_id = $dish->randomId();

$response = new DataResponse("Random dish ID generated.", $random_dish_id);
$response->send();
?>
