<?php
namespace AddonPaymentsSDK\Requests\Utils\Exceptions;

class ClientErrorException extends \Exception {
    protected int $statusCode;

    public function __construct(int $statusCode, string $message = "Client Error") {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode() : int {
        return $this->statusCode;
    }
}
