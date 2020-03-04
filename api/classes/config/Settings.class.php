<?php

require __DIR__ . "/../../../../config.php";

class Settings {
    private function __construct() {}

    const HOST = HOST;

    const DB_NAME = DB_NAME;

    const DB_USERNAME = DB_USERNAME;

    const DB_PASSWORD = DB_PASSWORD;

    const DB_USERS_TABLENAME = DB_USERS_TABLENAME;

    const JWT_SECRET_KEY = JWT_SECRET_KEY;

    const JWT_ALGORITHM = JWT_ALGORITHM;

    const JWT_ARRAY = JWT_ARRAY;
}

?>
