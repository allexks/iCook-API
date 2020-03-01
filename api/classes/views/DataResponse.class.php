<?php

require_once "classes/views/Response.class.php";

/**
 * An API response with a data array.
 */
class DataResponse extends Response {

    private $data;

    function __construct($message, $data) {
        parent::__construct(200, $message);
        $this->data = $data;
    }

    protected function toArray() {
        $result = parent::toArray();
        $result["data"] = $this->data;
        return $result;
    }
}

?>
