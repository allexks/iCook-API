<?php

require_once "classes/config/Settings.class.php";
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

class Token {

    public static function validate($user) {

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

            if (!$decoded) {
                return false;
            }

            $user->id = $decoded->data->id;
            $isValid = $user->verifyFromToken($token);

            if (!$isValid) {
                return false;
            }

            return $decoded->data;

        } catch (Exception $exception) {
            return false;
        }
    }

    public static function issueNew($user) {
        $now = time();
        $exp = $now + (Settings::JWT_EXP_MINUTES * 60);

        $token_arr = Settings::JWT_ARRAY;
        $token_arr["iat"] = $now;
        $token_arr["exp"] = $exp;
        $token_arr["data"] = $user->toArray();

        $token = JWT::encode($token_arr, Token::getSecretKey());

        if (!$user->saveNewToken($token, $now, $exp)) {
            return false;
        }

        return $token;
    }

    private static function getBearerToken() {
        $auth_header = apache_request_headers()["Authorization"] ?? "";
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
