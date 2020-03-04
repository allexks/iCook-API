<?php

require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/DataResponse.class.php";

$data = json_decode(file_get_contents("php://input"));
$token = $data->token ?? "";

$token_data = Token::validate($token);

if ($token_data) {
    $response = new DataResponse("Access granted.", $token_data);
    $response->send();
} else {
    $response = new Response(401, "Access denied.");
    $response->send();
}

?>
