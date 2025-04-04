<?php

namespace AddonPaymentsSDK\Requests;


use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\RequestsPaths;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\HttpExceptionHandler;
use AddonPaymentsSDK\Requests\Utils\Response;
use AddonPaymentsSDK\Config\Utils\Helpers;

class CreateRedirectionRequest
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
    private string $packageVersion;
    private mixed  $ivGenerator;

    public function __construct(callable  $ivGenerator = null)
    {
        $this->ivGenerator = $ivGenerator ?? function () : string {
            return openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        };

        $this->packageVersion = Helpers::getPackageVersion();
    }
    /**
     * Initializes the payment request with necessary parameters.
     * 
     * @param int $merchantId Merchant ID provided by the Addon Payments platform.
     * @param string $merchantPassword Merchant password for authentication.
     * @param array $otherConfigurations Additional configurations for the request.
     * @param bool|null $production Flag to indicate if the environment is production or staging.
     * @param string|null $baseUrl Base URL for the API requests.
     */

    public function initRedirectionPaymentRequest(int $merchantId, string $merchantPassword, array $otherConfigurations, bool|null $production, string|null $baseUrl): void
    {
        // Implement Redirection payment request logic here.
        $this->baseUrl = $baseUrl;
        $this->production = $production;
        if(count($otherConfigurations) > 0) {
            if (isset($otherConfigurations['merchantParams']) && !empty($otherConfigurations['merchantParams'])) {
                $otherConfigurations['merchantParams'] .= ';sdk:php;version:'. $this->packageVersion . ';type:Hosted';
            } else {
                $otherConfigurations['merchantParams'] = 'sdk:php;version:'. $this->packageVersion . ';type:Hosted';
            }
        }
        $data = $otherConfigurations;
        $data['merchantId'] = $merchantId;
        $this->merchantId = $merchantId;
        $this->otherConfigurations = $otherConfigurations;
        unset($data["header"]);
        $this->httpQuery = http_build_query($data);
        $this->merchantPassword = $merchantPassword;
    }

    /**
     * Encrypts the request data using AES-256-CBC encryption.
     * This method generates an IV (initialization vector) and encrypts the request data along with it.
     * It also computes a SHA256 hash signature of the formatted request data.
     * 
     * @throws \Exception If encryption fails or IV generation fails.
     */

    public function encryption(): void
    {
        try {
            // Implement Redirection payment request logic here.
            $this->formattedRequest = mb_convert_encoding($this->httpQuery, 'UTF-8', mb_list_encodings());
            if (!is_string($this->formattedRequest)) {
                throw new \Exception("Formatted request data is not a string.");
            }
            
            $this->iv = call_user_func($this->ivGenerator);

             /** @psalm-suppress TypeDoesNotContainType */
            if ($this->iv === false) {
                throw new \Exception("Failed to generate IV for encryption.");
            }
           
           
            $this->base64Iv = base64_encode($this->iv); // Generates a 16-byte random string
            $this->encryptedRequest = openssl_encrypt($this->formattedRequest, 'AES-256-CBC', $this->merchantPassword, 0, $this->iv);
            if ($this->encryptedRequest === false) {
                throw new \Exception("Encryption failed.");
            }
            $this->signature = hash('sha256', $this->formattedRequest);
        } catch (\Exception $e) {
            $this->logError("Encryption failed . Error: {$e->getMessage()}");
            throw $e; // Re-throw the exception to be handled by the caller
        }
    }

    public function requestCurl(string $url,array $data_url, array $headers) : array {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        curl_close($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP status code
        $curlErrorMessage = curl_error($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $this->logError("Communication error occurred while processing the request . Details: {$error_msg}");
            throw new NetworkException("{$error_msg}");
        }

        return ['response' => $response, 'status_code'=> $httpStatusCode, 'message' => $curlErrorMessage];
    }
    /**
     * Sends the encrypted request to the Addon Payments API and processes the response.
     * This method constructs the full request with headers and encrypted data, sends it using cURL, and processes the response.
     * 
     * @return array Contains the result and response data from the API.
     * @throws NetworkException If a network-related error occurs.
     * @throws \Exception If response handling fails or no response data is received.
     */

    public function sendRequest(): array
    {


        if (isset($this->baseUrl)) {
            $url = $this->baseUrl;
        } else {
            if ($this->production) {
                $url = RequestsPaths::HOSTED_PROD;
            } else {
                $url = RequestsPaths::HOSTED_STG;
            }
        }


        $data_url = [
            'merchantId' => $this->merchantId,
            'encrypted' => $this->encryptedRequest,
            'integrityCheck' => $this->signature,
        ];
        $headers = [
            'apiVersion: 5',
            'encryptionMode: CBC',
            'iv: ' . $this->base64Iv
        ];

        if (isset($this->otherConfigurations['header'])) {
            array_push($headers, 'Accept: application/json');
        }

        

        $requestCurl = $this->requestCurl($url,$data_url, $headers);
        $response = $requestCurl['response'];
        $curlErrorMessage = $requestCurl['message'];
        $httpStatusCode = $requestCurl['status_code'];

        if ($response) {
            $messsage = $response;
        } else {
            $messsage = $curlErrorMessage;
        }


        HttpExceptionHandler::handleHttpException($httpStatusCode, $messsage);



        // Process the response
        $responseData = $response;



        if (is_string($responseData)) {

            $this->logMessage("Request processed successfully." , $responseData, $headers , $data_url, "redirection", $this->otherConfigurations['paymentSolution'] , $this->otherConfigurations['merchantTransactionId'], $this->otherConfigurations);
            $this->redirectUrl = $responseData;
            if ($responseData == 'error') {
                throw new \Exception("Response come with error: ". $responseData);
            }
            // Payment URL received, redirect the customer
            return [
                'result' => 'Response',
                'response' => $responseData,
            ];
        } else {
            // Payment failed
            throw new \Exception("No response data received.");
            $this->logError("No response data received.");
            return [
                'result' => 'Fail',
                'response' => "Payment failed for. No response data received.",
            ];
        }
    }

    /**
     * Gets the HTTP query string of the request.
     * 
     * @return string|bool Returns the HTTP query string or false if not set.
     */

    public function getHttpQuery(): string|bool
    {
        return $this->httpQuery;
    }

    /**
     * Gets the formatted request data.
     * 
     * @return string|null Returns the formatted request data or null if not set.
     */

    public function getFormattedReq(): string|null
    {
        return $this->formattedRequest;
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
     * Gets the encrypted request data.
     * 
     * @return string|null Returns the encrypted request data or null if not set.
     */
    public function getEncryptedRequest(): string|null
    {
        return $this->encryptedRequest;
    }
    
    /**
     * Retrieves the response of the Hosted request.
     *
     * @return Response|null The response or null if not set.
     */
    public function getResponse() : ?Response
    {
        return new Response($this->redirectUrl);
    }





    // Other Redirection-specific methods can also go here.

    
}
