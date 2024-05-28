<?php
namespace AddonPaymentsSDK\Requests;

use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\RequestsPaths;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\HttpExceptionHandler;
use AddonPaymentsSDK\Requests\Utils\Response;
class CreateAuthTokenRequest {
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

   



    public ?string $authToken = null;

    /**
     * Initializes an authentication token request with necessary parameters.
     *
     * @param int $merchantId Merchant ID.
     * @param int $productId Product ID.
     * @param string $merchantKey Merchant key.
     * @param array $otherConfigurations Additional configurations.
     * @param null|bool $production Production mode flag.
     * @param null|string $baseUrl Base URL for the request.
     */
    public function initAuthTokentRequest(int $merchantId, int $productId, string $merchantKey, array $otherConfigurations, null|bool $production, null|string $baseUrl) : void  {
       
        $this->baseUrl = $baseUrl;
        $this->production = $production;
        $this->merchantId = $merchantId;
        $this->productId = $productId;
        $this->merchantKey = $merchantKey;
        if(count($otherConfigurations) > 0) {
            if (isset($otherConfigurations['merchantParams']) && !empty($otherConfigurations['merchantParams'])) {
                $otherConfigurations['merchantParams'] .= ';sdk:php;version:1.00;type:JsAuth';
            } else {
                $otherConfigurations['merchantParams'] = 'sdk:php;version:1.00;type:JsAuth';
            }
        }
        $this->otherConfigurations = $otherConfigurations;
    }

    public function requestCurl(string $url,string $requestJson, array $headers) : array {
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
        $responseRaw = curl_exec($curlSession);
        $httpStatusCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE); // Get the HTTP status code
        $curlErrorMessage = curl_error($curlSession);
        if(curl_errno($curlSession)) {
            $errorMsg = curl_error($curlSession);
            $this->logError("Communication error occurred while processing the request. Details: {$errorMsg}");
            throw new NetworkException("{$errorMsg}");
           
        } else {
            $this->logMessage("Request processed successfully. Response: {$responseRaw}", $headers , $requestJson, "jsAuth", "creditcards" , $this->otherConfigurations['merchantTransactionId'] ?? 'NoTxn');
        }

        return ['response' => $responseRaw, 'status_code'=> $httpStatusCode, 'message' => $curlErrorMessage];
    }

     /**
     * Sends the authentication token request to the server.
     *
     * @return bool True if the request was successful, false otherwise.
     * @throws NetworkException If a network error occurs during the request.
     */
    public function sendRequest() : bool {
       
        if(isset($this->baseUrl)) {
            $url = $this->baseUrl;
        } else {
            if($this->production) {
                $url = RequestsPaths::JS_AUTH_PROD;
            } else {
                $url = RequestsPaths::JS_AUTH_STG;
            }
        }
        $headers = array(
            'Content-Type: application/json',
        );

        if(isset($this->otherConfigurations['header'])) {
            array_push($headers, 'Accept: application/json');
        }

        // Build the data payload
        $requestPayload = array(
            'merchantId' => $this->merchantId,
            'merchantKey' => $this->merchantKey,
            'productId' => $this->productId,
        );

        foreach($this->otherConfigurations as $key => $value) {
            if($key != 'header')
                $requestPayload[$key] = $value;
        }

        // Generate the JSON request
        $requestJson = json_encode($requestPayload);

        // Initiate cURL session
        
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
        // Check for errors
        if($responseRaw === false || !is_string($responseRaw)) {
            $this->logError("cURL request failed or returned non-string response.");
            return false;
        }
        $this->response = $responseRaw;
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
     * Retrieves the response of the refund request.
     *
     * @return Response|null The response or null if not set.
     */
    public function getResponse() : ?Response
    {
        return new Response($this->response);
    }

   


}

?>