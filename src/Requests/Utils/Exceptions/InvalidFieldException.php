<?php 

namespace AddonPaymentsSDK\Requests\Utils\Exceptions;

class InvalidFieldException extends FieldException {
    public function __construct(string $message = "Invalid field", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}