<?php

include_once 'classes/models/User.class.php';
include_once 'classes/util/Token.class.php';
include_once 'classes/responses/Response.class.php';
include_once 'classes/responses/TokenResponse.class.php';

$user = new User($db);

$token_data = Token::validate($user);

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$post_data = json_decode(file_get_contents("php://input"));

$user->email = $post_data->email ?? "";

if ($user->emailExists() && $user->id != $token_data->id) {
    $response = new Response(401, "E-mail is already taken!");
    $response->send();
}

$user->firstname = $post_data->firstname ?? "";
$user->lastname = $post_data->lastname ?? "";
$user->password = $post_data->password ?? null;

if (!$user->update()) {
    $response = new Response(401, "Unable to update user.");
    $response->send();
}

$new_token = Token::issueNew($user);

if (!$new_token) {
    $response = new Response(500, "Could not issue new token.");
    $response->send();
}

$response = new TokenResponse("User was updated.", $new_token);
$response->send();

?>
