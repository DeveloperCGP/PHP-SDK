<?php

namespace AddonPaymentsSDK\QuixNotificationModel;

use AddonPaymentsSDK\QuixNotificationModel\WorkFlowResponse;
use AddonPaymentsSDK\QuixNotificationModel\Operation;
use AddonPaymentsSDK\QuixNotificationModel\Utils\OptionalTransactionParams;

class QuixTransaction
{
    private ?string $message = null;
    private ?Operation $operation = null;
    private ?string $status = null;
    private mixed $workFlowResponse = null;
    private ?int $operationSize = null;
    private mixed $optionalTransactionParams = null;

    public function __construct(?string $raw)
    {
        if ($raw === null)
            return;

        if (empty($raw))
            return;

        // First, attempt to decode the JSON
        $decoded = json_decode($raw, false);

        if (json_last_error() == JSON_ERROR_NONE) {
            // Handling JSON with or without a response key
            if (isset($decoded->response) && is_string($decoded->response)) {
                // Process embedded XML if 'response' contains an XML string
                $xmlObject = simplexml_load_string($decoded->response, "SimpleXMLElement", LIBXML_NOCDATA);

                if ($xmlObject !== false) {
                    $decoded = json_decode(json_encode($xmlObject), false);
                }
            } elseif (isset($decoded->response) && is_object($decoded->response)) {
                $this->processData($decoded->response);
            } elseif (isset($decoded->workFlowResponse) || isset($decoded->status) || isset($decoded->message)) {
                // Process JSON data directly if structured in the new format
                $this->processData($decoded);
                return;
            }
            $this->processData($decoded);
        } else {
            // Attempt to parse as plain XML if JSON parsing fails
            $xmlObject = simplexml_load_string($raw, "SimpleXMLElement", LIBXML_NOCDATA);
            if ($xmlObject !== false) {
                $decoded = json_decode(json_encode($xmlObject), false);
                $this->processData($decoded);
            }
        }

    }


    private function processData(object $data): void
    {
       
       
        $this->setMessage($data->message ?? null);
        $this->setStatus($data->status ?? null);
        $this->setWorkFlowResponse($data->workFlowResponse ?? null);
        $this->setOperationSize($data->operationSize ?? null);
        $this->setOptionalTransactionParams($data->optionalTransactionParams ?? null);

        if (isset($data->operationsArray)) {
            $this->setOperation($data->operationsArray[0]);
        }

        if (isset($data->operations)) {
            $this->setOperation($data->operations->operation);
        }
    }

   

    private function setMessage(?string $var): void
    {
        $this->message = $var;
    }

    private function setStatus(?string $var): void
    {
        $this->status = $var;
    }

    private function setWorkFlowResponse(mixed $var): void
    {
        $this->workFlowResponse = new WorkFlowResponse($var);
    }

    private function setOperationSize(?int $var): void
    {
        $this->operationSize = $var;
    }

    private function setOperation(mixed $var): void
    {
        $this->operation = new Operation($var);
    }

    private function setOptionalTransactionParams(mixed $var): void
    {
        $this->optionalTransactionParams = new OptionalTransactionParams($var);
    }

    /**
     * Get the message associated with the notification.
     *
     * @return string|null The message or null if not found.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the status associated with the notification.
     *
     * @return string|null The status or null if not found.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Get the size of the operation.
     *
     * @return int|null The operation size or null if not found.
     */
    public function getOperationSize(): ?int
    {
        return $this->operationSize;
    }

    /**
     * Get the workflow response associated with the notification.
     *
     * @return mixed The workflow response.
     */
    public function getWorkFlowResponse(): mixed
    {
        return $this->workFlowResponse;
    }

    /**
     * Get the operation details associated with the notification.
     *
     * @return null|Operation The operation details.
     */
    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    /**
     * Get the optional transaction parameters associated with the notification.
     *
     * @return mixed The optional transaction parameters.
     */
    public function getOptionalTransactionParams(): mixed
    {
        return $this->optionalTransactionParams;
    }
}
