<?php

require_once "classes/models/User.class.php";
require_once "classes/util/Token.class.php";
require_once "classes/responses/Response.class.php";
require_once "classes/responses/TokenResponse.class.php";

$data = json_decode(file_get_contents("php://input"));

$user = new User($db);
$user->email = $data->email ?? "";
$email_exists = $user->emailExists();

if (!$email_exists || !password_verify($data->password, $user->password)) {
    $response = new Response(401, "Failed login.");
    $response->send();
}

$token = Token::issueNew($user);

if (!$token) {
    $response = new Response(500, "Could not issue new token.");
    $response->send();
}

$response = new TokenResponse("Successful login", $token);
$response->send();

?>
