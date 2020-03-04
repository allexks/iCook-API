<?php

require_once "classes/models/User.class.php";

$data = json_decode(file_get_contents("php://input"));

$user = new User($db);
$user->firstname = $data->firstname ?? "";
$user->lastname = $data->lastname ?? "";
$user->email = $data->email ?? "";
$user->password = $data->password ?? "";

if (
    empty($user->firstname) ||
    empty($user->email) ||
    empty($user->password)
) {
    $response = new Response(400, "Please specify all needed info.");
    $response->send();
}

if ($user->emailExists()) {
    $response = new Response(400, "User with such e-mail alredy exists!");
    $response->send();
}

if (!$user->create()) {
    $response = new Response(500, "Unable to create new user.");
    $response->send();
}

$response = new Response(200, "Successfully created new user.");
$response->send();

?>
