<?php

require_once "classes/responses/Response.class.php";

/**
 * An API response containing a JWT.
 */
class TokenResponse extends Response {

    private $token;

    public function __construct($message, $token) {
        parent::__construct(200, $message);
        $this->token = $token;
    }

    protected function toArray() {
        $result = parent::toArray();
        $result["token"] = $this->token;
        return $result;
    }
}

?>
