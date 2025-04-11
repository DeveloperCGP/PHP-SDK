<?php

namespace AddonPaymentsSDK\NotificationModel\Operations;


class ThreeDsOperation
{

    private ?string $status;
    private ?string $merchantTransactionId;
    private ?string $transactionId;
    private ?string $payFrexTransactionId;
    private ?string $operationType;
    private ?string $currency;
    private ?string $amount;
    private ?RespCode $respCode;
    private ?string $service;
    private ?string $message;
    private ?PaymentDetails $paymentDetails;
    private ?string $redirectionResponse;
    private ?string $paymentCode;
    private ?string $paymentMessage;
    private ?MPI $mpi;

    public function __construct(mixed $operation)
    {
        $this->setStatus(isset($operation->status) ? trim((string) $operation->status) : null);
        $this->setTransactionId(isset($operation->transactionId) ? trim((string) $operation->transactionId) : null);
        $this->setPayFrexTransactionId(isset($operation->payFrexTransactionId) ? trim((string) $operation->payFrexTransactionId) : null);
        $this->setOperationType(isset($operation->operationType) ? trim( (string) $operation->operationType) : null);
        $this->setCurrency(isset($operation->currency) ? trim((string) $operation->currency) : null);
        $this->setAmount(isset($operation->amount) ? trim((string) $operation->amount) : null);
        $this->setRespCode(isset($operation->respCode) ? $operation->respCode : null);
        $this->setMerchantTransactionId(isset($operation->merchantTransactionId) ? trim((string) $operation->merchantTransactionId) : null );
        $this->setService(isset($operation->service) ? trim((string) $operation->service) : null);
        $this->setMessage(isset($operation->message) ? trim((string) $operation->message) : null);
        $this->setPaymentDetails(isset($operation->paymentDetails) ? $operation->paymentDetails : null);
        $this->setRedirectionResponse(isset($operation->redirectionResponse) ? trim((string) $operation->redirectionResponse) : null);
        $this->setPaymentCode(isset($operation->paymentCode) ? trim((string) $operation->paymentCode) : null);
        $this->setPaymentMessage(isset($operation->paymentMessage)? trim((string) $operation->paymentMessage) : null );
        $this->setMPI(isset($operation->mpi) ? $operation->mpi : null);
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
    private function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    private function setRespCode(mixed $respCode): void
    {
        $this->respCode = new RespCode($respCode);
    }
    private function setPaymentDetails(mixed $paymentDetails): void
    {
        $this->paymentDetails = new PaymentDetails($paymentDetails);
    }

    private function setRedirectionResponse(?string $redirectionResponse): void
    {
        $this->redirectionResponse = $redirectionResponse;
    }
    private function setService(?string $service): void
    {
        $this->service = $service;
    }

    private function setPaymentCode(?string $paymentCode): void
    {
        $this->paymentCode = $paymentCode;
    }

    private function setPaymentMessage(?string $paymentMessage): void
    {
        $this->paymentMessage = $paymentMessage;
    }

    private function setMPI(mixed $paymentDetails): void
    {
        $this->mpi = new MPI($paymentDetails);
    }

    // Getters

    /**
     * Get the status of the ThreeDs operation.
     *
     * @return string|null The status or null if not found.
     */
    public function getStatus(): ?string
    {
        return $this->status;
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
     * Get the PayFrex transaction ID of the ThreeDs operation.
     *
     * @return null|string The PayFrex transaction ID or null if not found.
     */
    public function getPayFrexTransactionId(): ?string
    {
        return $this->payFrexTransactionId;
    }

    /**
     * Get the operation type of the ThreeDs operation.
     *
     * @return string|null The operation type or null if not found.
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * Get the currency of the ThreeDs operation.
     *
     * @return string|null The currency or null if not found.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get the amount of the ThreeDs operation.
     *
     * @return string|null The amount or null if not found.
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Get the service of the ThreeDs operation.
     *
     * @return string|null The service or null if not found.
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * Get the message of the ThreeDs operation.
     *
     * @return string|null The message or null if not found.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the merchant transaction ID of the ThreeDs operation.
     *
     * @return string|null The merchant transaction ID or null if not found.
     */
    public function getMerchantTransactionId(): ?string
    {
        return $this->merchantTransactionId;
    }

    /**
     * Get the response code of the ThreeDs operation.
     *
     * @return RespCode|null The response code or null if not found.
     */
    public function getRespCode(): ?RespCode
    {
        return $this->respCode;
    }

    /**
     * Get the payment details of the ThreeDs operation.
     *
     * @return PaymentDetails|null The payment details or null if not found.
     */
    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

     /**
     * Get the redirection response of the ThreeDs operation.
     *
     * @return string|null The payment details or null if not found.
     */
    public function getRedirectionResponse(): ?string
    {
        return $this->redirectionResponse;
    }



    /**
     * Get the payment code of the ThreeDs operation.
     *
     * @return string|null The payment code or null if not found.
     */
    public function getPaymentCode(): ?string
    {
        return $this->paymentCode;
    }

    /**
     * Get the payment message of the ThreeDs operation.
     *
     * @return string|null The payment message or null if not found.
     */
    public function getPaymentMessage(): ?string
    {
        return $this->paymentMessage;
    }

    /**
     * Get the MPI of the ThreeDs operation.
     *
     * @return MPI|null The MPI or null if not found.
     */
    public function getMPI(): ?MPI
    {
        return $this->mpi;
    }


}
