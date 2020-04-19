<?php

require_once "classes/models/Recipe.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$recipe_id = $URI_PATH[3] ?? 0;
if (!$recipe_id) {
    $response = new Response(400, "No recipe id specfified.");
    $response->send();
}

$recipe = new Recipe($db);
$recipe->id = $recipe_id;

if (!$recipe->fetch()) {
    $response = new Response(404, "No recipe for the given id exists.");
    $response->send();
}

$response = new DataResponse("Recipe fetched.", $recipe);
$response->send();

?>
