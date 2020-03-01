<?php

header("Access-Control-Allow-Origin: http://localhost/restapitutorial/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "config/Database.class.php";
include_once "objects/User.class.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$posted_data = json_decode(file_get_contents("php://input"));

$user->firstname = $posted_data->firstname;
$user->lastname = $posted_data->lastname;
$user->email = $posted_data->email;
$user->password = $posted_data->password;

if (
    !empty($user->firstname) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()
){
    http_response_code(200);
    echo json_encode(array("message" => "User was created."));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user."));
}

?>
