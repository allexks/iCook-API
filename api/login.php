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

$user->email = $posted_data->email;
$email_exists = $user->emailExists();

include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if ($email_exists && password_verify($posted_data->password, $user->password)) {
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => $user->toArray()
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $key);

    echo json_encode(array(
        "message" => "Successful login.",
        "token" => $jwt
    ));
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Failed login."));
}

?>
