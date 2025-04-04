<?php

namespace AddonPaymentsSDK\NotificationModel\Operations;
use AddonPaymentsSDK\NotificationModel\Utils\ExtraDetailsProcessor;

class PaymentSolutionOperation
{

    private ?string $status;
    private ?string $merchantTransactionId;
    private ?string $paySolTransactionId;

    private ?string $transactionId;
    private ?string $operationType;
    private ?string $currency;
    private ?string $amount;
    private null|string|RespCode $respCode;

    private ?string $message;
    private ?PaymentDetails $paymentDetails;
    private ?string $paymentCode;
    private ?string $paymentMessage;
    private ?MPI $mpi;
    private mixed $details;
    private mixed $paymentMethod;
    private mixed $paymentSolution;
    private mixed $subscriptionPlan;
    private mixed $authCode;
    private mixed $optionalTransactionParams = null;

    public function __construct(mixed $operation)
    {
        //var_dump($operation);
        $this->setStatus(isset($operation->status) ? trim((string) $operation->status) : null);
        $this->setPaySolTransactionId(isset($operation->paySolTransactionId) ? trim((string) $operation->paySolTransactionId) : null);

        $this->setTransactionId(isset($operation->transactionId) ? trim((string) $operation->transactionId) : null);
        $this->setOperationType(isset($operation->operationType) ? trim((string) $operation->operationType) : null);
        $this->setDetails(isset($operation->details) ? trim((string) $operation->details) : null);
        $this->setCurrency(isset($operation->currency)? trim((string) $operation->currency) : null);
        $this->setAmount(isset($operation->amount)? trim((string) $operation->amount) : null);
        $this->setRespCode(isset($operation->respCode) ? $operation->respCode : null);
        $this->setMerchantTransactionId(isset($operation->merchantTransactionId)? trim((string) $operation->merchantTransactionId) : null);

        $this->setMessage(isset($operation->message)? trim((string) $operation->message) : null);
        $this->setPaymentDetails(isset($operation->paymentDetails)? $operation->paymentDetails : null);
        $this->setPaymentCode(isset($operation->paymentCode)? trim((string) $operation->paymentCode) : null);
        $this->setPaymentMessage(isset($operation->paymentCode)? trim((string) $operation->paymentMessage) : null);
        $this->setMPI(isset($operation->mpi)? $operation->mpi : null);
        $this->setPaymentMethod(isset($operation->paymentMethod)? $operation->paymentMethod : null);
        $this->setPaymentSolution(isset($operation->paymentSolution)? $operation->paymentSolution : null);
        $this->setSubscriptionPlan(isset($operation->subscriptionPlan)? $operation->subscriptionPlan : null);
        $this->setAuthCode(isset($operation->paymentSolution) ? $operation->paymentSolution : null);
        $this->setOptionalTransactionParams(isset($operation->optionalTransactionParams) ? $operation->optionalTransactionParams : null);
        
    }

    private function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    private function setPaySolTransactionId(?string $paySolTransactionId): void
    {
        $this->paySolTransactionId = $paySolTransactionId;
    }


    private function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
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



    private function setPaymentCode(?string $paymentCode): void
    {
        $this->paymentCode = $paymentCode;
    }

    private function setPaymentMessage(?string $paymentMessage): void
    {
        $this->paymentMessage = $paymentMessage;
    }

    private function setMPI(mixed $mpi): void
    {
        $this->mpi = new MPI($mpi);
    }

    private function setDetails(mixed $details): void
    {
        $this->details = $details;
    }

    private function setPaymentMethod(mixed $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    private function setPaymentSolution(mixed $paymentSolution): void
    {
        $this->paymentSolution = $paymentSolution;
    }

    private function setSubscriptionPlan(mixed $subscriptionPlan):void 
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    private function setAuthCode(mixed $authCode): void
    {
        $this->authCode = $authCode;
    }

    private function setOptionalTransactionParams(mixed $var): void
    {
        if ($var) $this->optionalTransactionParams = ExtraDetailsProcessor::processExtraDetails($var);
    }

    // Getters
    /**
     * Get the status of the Payment Solution operation.
     *
     * @return string|null The status or null if not found.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Get the PaySol transaction ID of the Payment Solution operation.
     *
     * @return string|null The PaySol transaction ID or null if not found.
     */
    public function getPaySolTransactionId(): ?string
    {
        return $this->paySolTransactionId;
    }

    /**
     * Get the transaction ID of the Payment Solution operation.
     *
     * @return string|null The transaction ID or null if not found.
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * Get the operation type of the Payment Solution operation.
     *
     * @return string|null The operation type or null if not found.
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * Get the currency of the Payment Solution operation.
     *
     * @return string|null The currency or null if not found.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get the amount of the Payment Solution operation.
     *
     * @return string|null The amount or null if not found.
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }


    /**
     * Get the message of the Payment Solution operation.
     *
     * @return string|null The message or null if not found.
     */

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the merchant transaction ID of the Payment Solution operation.
     *
     * @return string|null The merchant transaction ID or null if not found.
     */
    public function getMerchantTransactionId(): ?string
    {
        return $this->merchantTransactionId;
    }

    /**
     * Get the response code of the Payment Solution operation.
     *
     * @return RespCode|null|string The response code or null if not found.
     */
    public function getRespCode(): null|string|RespCode
    {
        return $this->respCode;
    }

    /**
     * Get the payment details of the Payment Solution operation.
     *
     * @return PaymentDetails|null The payment details or null if not found.
     */
    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

    /**
     * Get the payment code of the Payment Solution operation.
     *
     * @return string|null The payment code or null if not found.
     */
    public function getPaymentCode(): ?string
    {
        return $this->paymentCode;
    }

    /**
     * Get the payment message of the Payment Solution operation.
     *
     * @return string|null The payment message or null if not found.
     */
    public function getPaymentMessage(): ?string
    {
        return $this->paymentMessage;
    }

    /**
     * Get the MPI  of the Payment Solution operation.
     *
     * @return MPI|null The MPI or null if not found.
     */
    public function getMPI(): ?MPI
    {
        return $this->mpi;
    }

    /**
     * Get the details of the Payment Solution operation.
     *
     * @return mixed The details.
     */
    public function getDetails(): mixed
    {
        return $this->details;
    }

    /**
     * Get the payment method of the Payment Solution operation.
     *
     * @return mixed The payment method.
     */
    public function getPaymentMethod(): mixed
    {
        return $this->paymentMethod;
    }

    /**
     * Get the payment solution of the Payment Solution operation.
     *
     * @return mixed The payment solution.
     */
    public function getPaymentSolution(): mixed
    {
        return $this->paymentSolution;
    }

    /**
     * Get the Subscription plan of the Payment Solution operation.
     *
     * @return mixed The Subscription plan.
     */
    public function getSubscriptionPlan():Mixed
    {
        return $this->subscriptionPlan;
    }
    /**
     * Get the authorization code of the Payment Solution operation.
     *
     * @return mixed The authorization code.
     */
    public function getAuthCode(): mixed
    {
        return $this->authCode;
    }

    /**
     * Get the optional transaction parameters of the ThreeDs operation.
     *
     * @return array The optional transaction parameters or null if not found.
     */

     public function getOptionalTransactionParams(): ?array
     {
         return $this->optionalTransactionParams;
     }


}
