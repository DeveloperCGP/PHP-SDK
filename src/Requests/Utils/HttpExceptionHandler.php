<?php
namespace AddonPaymentsSDK\Requests\Utils;

use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;

class HttpExceptionHandler
{

    public static function handleHttpException(int $httpStatusCode, mixed $curlErrorMessage = ""): void
    {
        
        
        switch ($httpStatusCode) {
            case 400:
            case 401:
            case 403:
            case 404:
            case 408:
                throw new ClientErrorException($httpStatusCode, $curlErrorMessage);
                break;
            case 500:
                throw new ServerErrorException($httpStatusCode, $curlErrorMessage);
                break;
        }
    }
}