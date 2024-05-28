<?php
namespace AddonPaymentsSDK\Requests\Utils\Exceptions;

class ServerErrorException extends \Exception {
    protected int $statusCode;

    public function __construct(int $statusCode, string $message = "Server Error") {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
    public function getStatusCode() : int  {
        return $this->statusCode;
    }
}