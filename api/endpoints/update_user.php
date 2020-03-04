<?php

include_once 'classes/models/User.class.php';
include_once 'classes/util/Token.class.php';
include_once 'classes/responses/Response.class.php';
include_once 'classes/responses/TokenResponse.class.php';

$user = new User($db);

$post_data = json_decode(file_get_contents("php://input"));
$token = $post_data->token ?? "";

$token_data = Token::validate($token);

if (!$token_data) {
    $response = new Response(401, "Access denied.");
    $response->send();
}

$user->firstname = $post_data->firstname ?? "";
$user->lastname = $post_data->lastname ?? "";
$user->email = $post_data->email ?? "";
$user->password = $post_data->password ?? null;
$user->id = $token_data->id;

if ($user->update()) {
    $new_token = Token::issueNew($user);
    $response = new TokenResponse("User was updated.", $new_token);
    $response->send();
} else {
    $response = new Response(401, "Unable to update user.");
    $response->send();
}

?>
