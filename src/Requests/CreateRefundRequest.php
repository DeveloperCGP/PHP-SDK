<?php

namespace AddonPaymentsSDK\Requests;

use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\RequestsPaths;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\HttpExceptionHandler;
use AddonPaymentsSDK\Requests\Utils\Response;
use AddonPaymentsSDK\Config\Utils\Helpers;

class CreateRefundRequest
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
    public ?string $response = null;
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
     * Initializes a refund payment request with necessary parameters.
     *
     * @param int $merchantId Merchant ID.
     * @param string $merchantPassword Merchant password.
     * @param array $otherConfigurations Additional configurations.
     * @param bool|null $production Indicates if the request is for production.
     * @param string|null $baseUrl Base URL for the request.
     */
    public function initRefundPaymentRequest(int $merchantId, string $merchantPassword, array $otherConfigurations, bool|null $production, string|null $baseUrl): void
    {
        // Implement Redirection payment request logic here.
        $this->baseUrl = $baseUrl;
        $this->production = $production;
        if (count($otherConfigurations) > 0) {
            if (isset($otherConfigurations['merchantParams']) && !empty($otherConfigurations['merchantParams'])) {
                $otherConfigurations['merchantParams'] .= ';sdk:php;version:'. $this->packageVersion . ';type:Refund';
            } else {
                $otherConfigurations['merchantParams'] = 'sdk:php;version:'. $this->packageVersion . ';type:Refund';
            }
        }
        $this->otherConfigurations = $otherConfigurations;
        $data = $otherConfigurations;
        unset($data["header"]);
        $data['merchantId'] = $merchantId;
        $this->merchantId = $merchantId;
        $this->httpQuery = http_build_query($data);
        $this->merchantPassword = $merchantPassword;
    }

    public function requestCurl(string $url, array $data_url, array $headers): array
    {
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

        return ['response' => $response, 'status_code' => $httpStatusCode, 'message' => $curlErrorMessage];
    }

    /**
     * Encrypts the refund request data for secure transmission.
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

    /**
     * Sends the refund request to the server.
     *
     * @return array An array containing the result and response of the request.
     * @throws NetworkException If a network error occurs during the request.
     */
    public function sendRequest(): array
    {


        if (isset($this->baseUrl)) {
            $url = $this->baseUrl;
        } else {
            if ($this->production) {
                $url = RequestsPaths::REBATE_PROD;
            } else {
                $url = RequestsPaths::REBATE_STG;
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

        $requestCurl = $this->requestCurl($url, $data_url, $headers);
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
            $this->logMessage("Request processed successfully." , $responseData, $headers, $data_url, "refund", "creditcards", $this->otherConfigurations['merchantTransactionId'], $this->otherConfigurations);

            $this->response = $responseData;
            return [
                'result' => 'success',
                'response' => $responseData,
            ];
        } else {
            // Payment failed
            $this->logError("No response data received.");
            return [
                'result' => 'Fail',
                'response' => 'ERROR',
            ];
        }
    }

    /**
     * Gets the HTTP query string of the request.
     *
     * @return string|bool The HTTP query string or false if not set.
     */
    public function getHttpQuery(): string|bool
    {
        return $this->httpQuery;
    }

    /**
     * Gets the formatted request string.
     *
     * @return string|null The formatted request or null if not set.
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
     * Gets the encrypted request string.
     *
     * @return string|null The encrypted request or null if not set.
     */
    public function getEncryptedRequest(): string|null
    {
        return $this->encryptedRequest;
    }

    /**
     * Retrieves the response of the refund request.
     *
     * @return Response|null The response or null if not set.
     */
    public function getResponse(): ?Response
    {
        return new Response($this->response);
    }

    // Other Redirection-specific methods can also go here.
}
