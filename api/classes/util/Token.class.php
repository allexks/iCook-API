<?php

require_once "classes/config/Settings.class.php";
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

class Token {

    public static function validate() {

        $token = Token::getBearerToken();

        if (!$token) {
            return false;
        }

        try {
            $decoded = JWT::decode(
                $token,
                Token::getSecretKey(),
                Token::getAlgorithmArr()
            );
            return $decoded->data;

        } catch (Exception $exception) {
            return false;
        }
    }

    public static function issueNew($user) {
        $token_arr = Settings::JWT_ARRAY;
        $token_arr["data"] = $user->toArray();

        $token = JWT::encode($token_arr, Token::getSecretKey());
        return $token;
    }

    private static function getBearerToken() {
        $auth_header = apache_request_headers()["Authorization"] ?? "";
        if (!preg_match('/^Bearer/', $auth_header)) {
            return "";
        }
        return ltrim(trim($auth_header, "Bearer"));
    }

    private static function getSecretKey() {
        return Settings::JWT_SECRET_KEY;
    }

    private static function getAlgorithmArr() {
        return [Settings::JWT_ALGORITHM];
    }
}

?>
