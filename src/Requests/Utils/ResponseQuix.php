<?php

namespace AddonPaymentsSDK\Requests\Utils;


use AddonPaymentsSDK\QuixNotificationModel\QuixTransaction;

class ResponseQuix
{

    private ?string $rawResponse;
    private ?string $nemuruCartHash;
    private ?string $nemuruAuthToken;
    private ?string $nemuruDisableFormEdition;
    public function __construct(string $rawResponse = null)
    {

        $this->rawResponse = $rawResponse;
        $this->nemuruCartHash = null;
        $this->nemuruAuthToken = null;
        $this->nemuruDisableFormEdition = null;
        if ($rawResponse != null) {
            if ($this->isJsonOrXml($rawResponse)) {
                $response = new QuixTransaction($rawResponse);
                $nemuruCartHash = $response->getOperation()?->getPaymentDetails()?->getExtraDetails()?->getNemuruCartHash();
                $nemuruAuthToken = $response->getOperation()?->getPaymentDetails()?->getExtraDetails()?->getNemuruAuthToken();
                $nemuruDisableFormEdition = $response->getOperation()?->getPaymentDetails()?->getExtraDetails()?->getNemuruDisableFormEdition();

                $this->nemuruCartHash = $nemuruCartHash;
                $this->nemuruAuthToken = $nemuruAuthToken;
                $this->nemuruDisableFormEdition = $nemuruDisableFormEdition;
            }
        }
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
     * Retrieves the Nemuru Cart Hash from the response.
     *
     * @return string|null The Nemuru Cart Hash or null if not set.
     */
    public function getNemuruCartHash(): ?string
    {
        return $this->nemuruCartHash;
    }

    /**
     * Retrieves the Nemuru Auth Token from the response.
     *
     * @return string|null The Nemuru Auth Token or null if not set.
     */
    public function getNemuruAuthToken(): ?string
    {
        return $this->nemuruAuthToken;
    }

    public function getNemuruDisableFormEdition(): ?string
    {
        return $this->nemuruDisableFormEdition;
    }








    function isJsonOrXml(string $data): bool
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
