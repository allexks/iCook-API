<?php

/**
 * A basic API response.
 */
class Response {

    private $code;
    private $message;

    public function __construct($httpcode, $message) {
        $this->code = $httpcode;
        $this->message = $message;
    }

    public function send() {
        http_response_code($this->code);
        echo $this->jsonEncoded();
        exit(0);
    }

    protected function toArray() {
        return ["message" => $this->message];
    }

    private function jsonEncoded() {
        return json_encode($this->toArray());
    }
}


?>
