<?php

namespace AddonPaymentsSDK\Requests\Utils;

use AddonPaymentsSDK\NotificationModel\Transaction;

class Response
{
    private ?string $redirectUrl;
    private mixed $rawResponse;
    private mixed $authToken;
    private mixed $transaction;
    public function __construct(mixed $rawResponse = null)
    {
        $this->rawResponse = $rawResponse;
        $this->redirectUrl = null;
        $this->authToken = null;
        if ($rawResponse != null) {
            if ($this->isValidUrl($rawResponse)) {
                $this->redirectUrl = $rawResponse;
            } else if ($this->isJsonWithAuthToken($rawResponse)) {
                $data = json_decode($rawResponse);
                $this->authToken = $data->authToken;
            } elseif ($this->isJsonOrXml($rawResponse)) {
                $notifiction = new Transaction($rawResponse);
                $redirectionURL = $notifiction->getOperations()?->getThreeDsOperation()?->getRedirectionResponse();
                $this->transaction = $notifiction;
                if ($redirectionURL != null) $this->redirectUrl = str_replace("redirect:", "", $redirectionURL);
            }
        }
    }

    /**
     * Gets the redirect URL from the response.
     * 
     * @return string|null Returns the redirect URL or null if not set.
     */

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * Retrieves the authentication token obtained from the request.
     *
     * @return string|null The authentication token or null if not set.
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * Gets the response of the H2H request.
     *
     * @return mixed The response or null if not set.
     */
    public function getRawResponse(): mixed
    {
        return $this->rawResponse;
    }

    /**
     * Gets the response if xml or json parsed.
     *
     * @return mixed The response or null if not set.
     */
    public function getTransaction(): mixed
    {
        return $this->transaction;
    }


    private function isValidUrl(string $url): bool
    {
        // Check if the input string is a valid URL
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    private function isJsonWithAuthToken(string $response) : bool
    {
        // Decode the JSON string to an object
        $data = json_decode($response);

        // Check if json_decode() returned a valid object and if 'authtoken' is set in this object
        if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
            // Check if 'authtoken' exists and matches the specific value
            return isset($data->authToken);
        }

        // Return false if it's not valid JSON or 'authtoken' isn't set or doesn't match
        return false;
    }

    function isJsonOrXml(string $data) : bool
    {

        json_decode($data);
        if (json_last_error() == JSON_ERROR_NONE) {
            return true;
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data);
        if ($xml !== false) {
            return true;  // 
        }

        return false;
    }
}
