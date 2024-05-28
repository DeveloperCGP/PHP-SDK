<?php

namespace AddonPaymentsSDK\NotificationModel;

class Transaction
{
    private ?string $message;
    private ?Operations $operations;
    private ?string $status;
    private mixed $workFlowResponse;

    private mixed $optionalTransactionParams;

    public function __construct(string $raw)
    {
        $this->message = null;
        $this->status = null;
        $this->workFlowResponse = null;
        $this->operations = null;

        if (empty($raw)) {
            return;
        }

        $decoded = json_decode($raw, false);

        if (json_last_error() == JSON_ERROR_NONE) {

            if (isset($decoded->response) && is_string($decoded->response)) {
                $xmlObject = simplexml_load_string($decoded->response, "SimpleXMLElement", LIBXML_NOCDATA);
                if ($xmlObject !== false) {
                    $decoded = json_decode(json_encode($xmlObject), false);
                }
            } elseif (isset($decoded->response) && is_object($decoded->response)) {
                $this->processData($decoded->response);
            } elseif (isset($decoded->workFlowResponse) || isset($decoded->status) || isset($decoded->message)) {
                $this->processData($decoded);
                return;
            }
            $this->processData($decoded);
        } else {
            $xmlObject = simplexml_load_string($raw, "SimpleXMLElement", LIBXML_NOCDATA);
            if ($xmlObject !== false) {
                $decoded = json_decode(json_encode($xmlObject), false);
                $this->processData($decoded);
            }
        }
    }

    private function processData(object $data): void
    {

        $this->setMessage(isset($data->message) ? trim((string) $data->message) :  null);
        $this->setStatus(isset($data->status) ? trim((string) $data->status) : null);
        $this->setWorkFlowResponse(isset($data->workFlowResponse) ? $data->workFlowResponse : null);

        if (isset($data->operationsArray)) {
            $this->setOperations($data->operationsArray);
        }

        if (isset($data->operations)) {
            $this->setOperations($data->operations->operation);
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
        if ($var) $this->workFlowResponse = new WorkFlowResponse($var);
    }



    private function setOperations(mixed $var): void
    {
        $this->operations = new Operations($var);
    }



    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }



    public function getWorkFlowResponse(): WorkFlowResponse
    {
        return $this->workFlowResponse;
    }

    public function getOperations(): ?Operations
    {
        return $this->operations;
    }

    public function getOptionalTransactionParams(): mixed
    {
        return $this->optionalTransactionParams;
    }
}
