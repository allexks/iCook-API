<?php

require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$token_data = Token::validate();

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$response = new DataResponse("Access granted.", $token_data);
$response->send();

?>
