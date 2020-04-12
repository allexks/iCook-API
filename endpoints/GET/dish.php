<?php

require_once "classes/models/Dish.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$dish_id = $URI_PATH[3] ?? 0;
if (!$dish_id) {
    $response = new Response(400, "No dish id specfified.");
    $response->send();
}

$dish = new Dish($db);
$dish->id = $dish_id;

if (!$dish->fetch()) {
    $response = new Response(400, "No dish for the given id exists.");
    $response->send();
}

$response = new DataResponse("Dish fetched.", $dish);
$response->send();

?>
