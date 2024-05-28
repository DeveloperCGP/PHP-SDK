<?php
namespace AddonPaymentsSDK\NotificationModel\Operations;

use AddonPaymentsSDK\NotificationModel\Operations\RespCode;

class TRAOperation
{
    private ?string $status;
    private ?string $merchantTransactionId;
    private ?string $paySolTransactionId;
    private ?string $transactionId;
    private ?string $payFrexTransactionId;
    private ?string $operationType;
    private ?string $currency;
    private ?string $amount;
    private string|RespCode|null $respCode;
    private ?string $service;

    public function __construct(mixed $operation)
    { 
         
        $this->setStatus(isset($operation->status) ? trim((string) $operation->status) : null);
        $this->setPaySolTransactionId(isset($operation->paySolTransactionId) ? trim((string) $operation->paySolTransactionId) : null);
        $this->setTransactionId(isset($operation->transactionId) ? trim((string) $operation->transactionId) : null);
        $this->setPayFrexTransactionId(isset($operation->payFrexTransactionId) ? trim((string) $operation->payFrexTransactionId) : null);
        $this->setOperationType(isset($operation->operationType) ? trim((string) $operation->operationType) : null);
        $this->setCurrency(isset($operation->currency) ? trim((string) $operation->currency) : null);
        $this->setAmount(isset($operation->amount) ? trim((string) $operation->amount) : null);
        $this->setRespCode(isset($operation->respCode) ? $operation->respCode : null);
        $this->setMerchantTransactionId(isset($operation->merchantTransactionId) ? trim((string) $operation->merchantTransactionId) : null);
        $this->setService(isset($operation->service) ? trim((string) $operation->service)  : null);
    }

    // Setters


    private function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    private function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    private function setPaySolTransactionId(?string $paySolTransactionId): void
    {
        $this->paySolTransactionId = $paySolTransactionId;
    }
    private function setPayFrexTransactionId(?string $payFrexTransactionId): void
    {
        $this->payFrexTransactionId = $payFrexTransactionId;
    }

    private function setOperationType(?string $operationType): void
    {
        $this->operationType = $operationType;
    }

    private function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    private function setMerchantTransactionId(?string $merchantTransactionId): void
    {
        $this->merchantTransactionId = $merchantTransactionId;
    }

    private function setAmount(?string $amount): void
    {
        $this->amount = $amount;
    }

    private function setRespCode(mixed $respCode): void
    {
        $this->respCode = new RespCode($respCode);
    }

    private function setService(?string $service): void
    {
        $this->service = $service;
    }

    // Getters

    /**
     * Get the status of the TRA operation.
     *
     * @return string|null The status or null if not found.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Get the PaySol transaction ID of the TRA operation.
     *
     * @return string|null The PaySol transaction ID or null if not found.
     */
    public function getPaySolTransactionId(): ?string
    {
        return $this->paySolTransactionId;
    }

    /**
     * Get the PayFrex transaction ID of the TRA operation.
     *
     * @return string|null The transaction ID or null if not found.
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }


    /**
     * Get the PayFrex transaction ID of the TRA operation.
     *
     * @return null|string The PayFrex transaction ID or null if not found.
     */
    public function getPayFrexTransactionId(): ?string
    {
        return $this->payFrexTransactionId;
    }

    /**
     * Get the operation type of the TRA operation.
     *
     * @return string|null The operation type or null if not found.
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * Get the currency of the TRA operation.
     *
     * @return string|null The currency or null if not found.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get the amount of the TRA operation.
     *
     * @return string|null The amount or null if not found.
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Get the service of the TRA operation.
     *
     * @return string|null The service or null if not found.
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * Get the merchant transaction ID of the TRA operation.
     *
     * @return string|null The merchant transaction ID or null if not found.
     */
    public function getMerchantTransactionId(): ?string
    {
        return $this->merchantTransactionId;
    }

    /**
     * Get the response code of the TRA operation.
     *
     * @return RespCode|null|string The response code, null if not found, or an instance of RespCode.
     */
    public function getRespCode(): null|RespCode|string
    {
        return $this->respCode;
    }
}

?>