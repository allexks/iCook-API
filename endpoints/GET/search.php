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

// Sort results

function comparator($a, $b) {
    global $term;

    if ($a == $b) {
        return 0;
    }

    $lev_a = levenshtein($a->name, $term);
    $lev_b = levenshtein($b->name, $term);

    if ($lev_a == $lev_b) {
        return 0;
    }

    return ($lev_a < $lev_b) ? -1 : 1;
}

usort($dishes, "comparator");

$response = new DataResponse("Search results ready.", $dishes);
$response->send();

?>
