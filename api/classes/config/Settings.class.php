<?php

class Settings {
    private function __construct() {}

    const HOST = "127.0.0.1";

    const DB_NAME = "";

    const DB_USERNAME = "";

    const DB_PASSWORD = "";

    const DB_USERS_TABLENAME = "users";

    const JWT_SECRET_KEY = "";

    const JWT_ALGORITHM = "HS256";

    public static function getJWTArray() {
        return array(
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" => 1357999522,
            "nbf" => 1358000000,
        );
    }
}

?>
