<?php

namespace AddonPaymentsSDK\Requests;

use AddonPaymentsSDK\Requests\Utils\Response;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\RequestsPaths;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\HttpExceptionHandler;
use AddonPaymentsSDK\Requests\Utils\ResponseQuix;
use AddonPaymentsSDK\Config\Utils\Helpers;

/**
 * Class to handle Quix Charge Requests.
 */
class CreateQuixChargeRequest
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
    public ?string $merchantKey = null;
    private ?string $nemuruAuthToken = null;
    private ?string $nemuruDisableFormEdition = null;
    private ?string $nemuruCartHash = null;
    public ?int $productId = null;
    public ?string $response = '';
    private string $packageVersion;
   

    public function __construct()
    {
        $this->packageVersion = Helpers::getPackageVersion();
    }

    /**
     * Initializes a Quix charge request with necessary parameters.
     *
     * @param int $merchantId Merchant ID.
     * @param int $productId Product ID.
     * @param string $merchantKey Merchant key.
     * @param array $otherConfigurations Additional configurations.
     * @param bool|null $production Production mode flag.
     * @param string|null $baseUrl Base URL for the request.
     */
    public function initChargeRequest(int $merchantId, int $productId, ?string $merchantKey, array $otherConfigurations, bool|null $production, string|null $baseUrl): void
    {
        $this->production = $production;
        $this->baseUrl = $baseUrl;
        $this->merchantId = $merchantId;
        $this->productId = $productId;
        $this->merchantKey = $merchantKey;
        if(count($otherConfigurations) > 0) {
            if (isset($otherConfigurations['merchantParams']) && !empty($otherConfigurations['merchantParams'])) {
                $otherConfigurations['merchantParams'] .= ';sdk:php;version:'. $this->packageVersion . ';type:QuixCharge';
            } else {
                $otherConfigurations['merchantParams'] = 'sdk:php;version:'. $this->packageVersion . ';type:QuixCharge';
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
        $responseRaw = curl_exec($curlSession);
        $httpStatusCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE); // Get the HTTP status code
        $curlErrorMessage = curl_error($curlSession);
        if (curl_errno($curlSession)) {
            $errorMsg = curl_error($curlSession);
            $this->logError("Communication error occurred while processing the request. Details: {$errorMsg}");
            throw new NetworkException("{$errorMsg}");

        } else {
            $this->logMessage("Request processed successfully." , $responseRaw, $headers , $requestJson, "jsCharge", "quix" , $this->otherConfigurations['merchantTransactionId']);
       }
 
         return ['response' => $responseRaw, 'status_code'=> $httpStatusCode, 'message' => $curlErrorMessage];
     }

    /**
     * Sends the Quix charge request to the server.
     *
     * @return bool True if the request was successful, false otherwise.
     * @throws NetworkException If a network error occurs during the request.
     */
    public function sendRequest(): bool
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
            'prepayToken:' . $this->otherConfigurations['prepayToken']
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
            if ($key == 'paysolExtendedData') {
                $requestPayload[$key] = $this->encodeDates($value);

            } else if ($key != 'prepayToken' && $key != 'header')
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

        // Check if authorization was successful
        if (is_string($responseRaw)) {
            $this->response = $responseRaw;
        } else {

            $this->logError("Payment failed. No response received.");
            return false;
        }
        return true;
    }

    /**
     * Encodes dates within the 'paysolExtendedData' field for proper handling.
     *
     * @param string $paysolExtendedData The extended data to be encoded.
     * @return string The encoded data.
     */
    private function encodeDates(string $paysolExtendedData): string
    {

        // Decode the JSON data to an associative array
        $data = json_decode($paysolExtendedData, true);

        // Check if items exist and iterate through them
        // Check if items exist and iterate through them
        if (isset($data['cart']) && isset($data['cart']['items']) && is_array($data['cart']['items'])) {

            foreach ($data['cart']['items'] as $key => $item) {
                // URL encode start_date and end_date if they exist
                if (isset($item['article']['start_date'])) {

                    $data['cart']['items'][$key]['article']['start_date'] = urlencode($item['article']['start_date']);

                }
                if (isset($item['article']['end_date'])) {
                    $data['cart']['items'][$key]['article']['end_date'] = urlencode($item['article']['end_date']);
                }

                // URL encode checkin_date and checkout_date if they exist
                if (isset($item['article']['checkin_date'])) {
                    $data['cart']['items'][$key]['article']['checkin_date'] = urlencode($item['article']['checkin_date']);

                }
                if (isset($item['article']['checkout_date'])) {
                    $data['cart']['items'][$key]['article']['checkout_date'] = urlencode($item['article']['checkout_date']);
                }

                // URL encode customer_member_since and departure_date if they exist
                if (isset($item['article']['customer_member_since'])) {
                    $data['cart']['items'][$key]['article']['customer_member_since'] = urlencode($item['article']['customer_member_since']);
                }
                if (isset($item['article']['departure_date'])) {
                    $data['cart']['items'][$key]['article']['departure_date'] = urlencode($item['article']['departure_date']);
                }
            }
        }

        // Return the modified data as JSON
        return json_encode($data);
    }


    private function setRepsonse(mixed $data) : ?Response 
    {
        return new Response($data);
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
     * Retrieves the response of the Quix charge request.
     *
     * @return ResponseQuix|null The response or null if not set.
     */
    public function getResponse(): ?ResponseQuix
    {
        return new ResponseQuix($this->response);
    }

    
}
