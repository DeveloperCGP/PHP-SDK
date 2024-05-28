<?php

namespace AddonPaymentsSDK\Requests;

use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\RequestsPaths;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\HttpExceptionHandler;
use AddonPaymentsSDK\Requests\Utils\Response;

class CreateChargeRequest
{
    use LoggerTrait;

    private ?string $baseUrl = null;

    private ?bool $production = null;
    public ?int $merchantId = null;
    public string $merchantPassword = '';
    public string $httpQuery = '';
    public ?string $formattedRequest = null;
    public ?string $iv = null;
    public ?string $base64Iv = null;
    public ?string $encryptedRequest = null;
    public ?string $signature = null;
    public ?string $redirectUrl = null;

    private array $otherConfigurations = [];


    public null | string | bool $response = null;

    
  
    public ?string $merchantKey = null;
  
    public ?int  $productId = null;

   

/**
     * Initializes a charge request with necessary parameters.
     *
     * @param int $merchantId Merchant ID.
     * @param int $productId Product ID.
     * @param string $merchantKey Merchant key.
     * @param array $otherConfigurations Additional configurations.
     * @param bool|null $production Production mode flag.
     * @param string|null $baseUrl Base URL for the request.
     */
    public function initChargeRequest(int $merchantId, int $productId, ?string $merchantKey, array $otherConfigurations, bool|null $production, string|null $baseUrl) : void 
    {
        $this->production = $production;
        $this->baseUrl = $baseUrl;
        $this->merchantId = $merchantId;
        $this->productId = $productId;
        $this->merchantKey = $merchantKey;
        if(count($otherConfigurations) > 0) {
            if (isset($otherConfigurations['merchantParams']) && !empty($otherConfigurations['merchantParams'])) {
                $otherConfigurations['merchantParams'] .= ';sdk:php;version:1.00;type:JsCharge';
            } else {
                $otherConfigurations['merchantParams'] = 'sdk:php;version:1.00;type:JsCharge';
            }
        }
        $this->otherConfigurations = $otherConfigurations;
    }

    public function requestCurl(string $url,string $requestJson, array $headers) : array {
       // Initiate cURL session
       $curlSession = curl_init();

       // Set cURL options
       curl_setopt($curlSession, CURLOPT_HEADER, false);
       curl_setopt($curlSession, CURLOPT_CONNECTTIMEOUT, 10);
       curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);
       curl_setopt($curlSession, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($curlSession, CURLOPT_POST, true);
       curl_setopt($curlSession, CURLOPT_POSTFIELDS, $requestJson);
       curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2);
       curl_setopt($curlSession, CURLOPT_TIMEOUT, 10);
       curl_setopt($curlSession, CURLOPT_URL, $url);

       // Execute the cURL request
       /** @var string|false $responseRaw */
       $responseRaw = curl_exec($curlSession);

       $httpStatusCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE); // Get the HTTP status code
       $curlErrorMessage = curl_error($curlSession);
       if (curl_errno($curlSession)) {
           /**
            * @var string|false $responseRaw The response from curl_exec, or false on failure.
            */
           $errorMsg = curl_error($curlSession);
           $this->logError("Communication error occurred while processing the request. Details: {$errorMsg}");
           throw new NetworkException("{$errorMsg}");
       } else {
           $this->logMessage("Request processed successfully. Response: \n{$responseRaw}", $headers , $requestJson, "jsCharge", "creditcards" , $this->otherConfigurations['merchantTransactionId']);
       }

        return ['response' => $responseRaw, 'status_code'=> $httpStatusCode, 'message' => $curlErrorMessage];
    }

     /**
     * Sends the charge request to the server.
     *
     * @return bool True if the request was successful, false otherwise.
     * @throws NetworkException If a network error occurs during the request.
     */
    public function sendRequest() : bool
    {


        if (isset($this->baseUrl)) {
            $url = $this->baseUrl;
        } else {
            if ($this->production) {
                $url = RequestsPaths::JS_CHARGE_PROD;
            } else {
                $url = RequestsPaths::JS_CHARGE_STG;
            }
        }
        $headers = array(
            'Content-Type: application/json',
            'prepayToken:' . $this->otherConfigurations['prepayToken'],
            'apiVersion: 5'
        );


        if (isset($this->otherConfigurations['header'])) {
            array_push($headers, 'Accept: application/json');
        }

        // Build the data payload
        $requestPayload = array(
            'merchantId' => $this->merchantId,
            'productId' => $this->productId,
        );

        if (isset($this->merchantKey)) {
            $requestPayload['merchantKey'] = $this->merchantKey;
        }

        foreach ($this->otherConfigurations as $key => $value) {
            if ($key != 'prepayToken' && $key != 'header')
                $requestPayload[$key] = $value;
        }

        // Generate the JSON request
        $requestJson = json_encode($requestPayload);

        $requestCurl = $this->requestCurl($url, $requestJson, $headers);
        $responseRaw = $requestCurl['response'];
        $curlErrorMessage = $requestCurl['message'];    
        $httpStatusCode = $requestCurl['status_code'];
        if ($responseRaw) {
            $messsage = $responseRaw;
        } else {
            $messsage = $curlErrorMessage;
        }


        HttpExceptionHandler::handleHttpException($httpStatusCode, $messsage);
       
      
        // Decode the response
        /** @var string|false $responseRaw */
        $response = $responseRaw;

        // Check if authorization was successful
        if ($responseRaw !== false) {
            $this->response = $response;
        } else {

            $this->logError("Payment failed. No response received.");
            return false;
        }
        return true;
    }

     /**
     * Gets the payment request data.
     * 
     * @return array Returns payment request data array.
     */

     public function getOtherConfigurations(): array
     {
         return $this->otherConfigurations;
     }

    /**
     * Retrieves the response of the Charge request.
     *
     * @return Response|null The response or null if not set.
     */
    public function getResponse() : ?Response
    {
        return new Response($this->response);
    }

  
}
