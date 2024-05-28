<?php

namespace AddonPaymentsSDK\QuixNotificationModel;

use AddonPaymentsSDK\QuixNotificationModel\Utils\MPI;
use AddonPaymentsSDK\QuixNotificationModel\Utils\RespCode;
use AddonPaymentsSDK\QuixNotificationModel\Utils\PaymentDetails;

class Operation
{
    private ?string $type = null;
    private ?string $status = null;
    private mixed $merchantTransactionId = null;  // Type not specified, assumed mixed
    private mixed $paySolTransactionId = null;    // Type not specified, assumed mixed
    private mixed $payFrexTransactionId = null;  // Nullable type
    private mixed $transactionId = null;  // Type not specified, assumed mixed
    private ?string $operationType = null;
    private ?string $currency = null;
    private ?float $amount = null;
    private ?RespCode $respCode = null;
    private mixed $service = null;   // Type not specified, assumed mixed
    private ?string $message = null;
    private ?PaymentDetails $paymentDetails = null;
    private mixed $paymentCode = null;  // Type not specified, assumed mixed
    private ?string $paymentMessage = null;
    private ?MPI $mpi = null;
    private mixed $details = null;  // Type not specified, assumed mixed
    private mixed $paymentMethod = null;  // Type not specified, assumed mixed
    private mixed $paymentSolution = null;  // Type not specified, assumed mixed
    private mixed $authCode = null;  // Type not specified, assumed mixed
    private ?string $checkoutURL = null;  // Nullable type
    private mixed $subscriptionPlan = null;  // Nullable type
    private ?float $fee = null;  // Nullable type
    private mixed $checkFields = null;  // Nullable type
    private mixed $statusType3DS = null;  // Nullable type
    private mixed $rad = null;  // Nullable type
    private ?string $radMessage = null;  // Nullable type
    private mixed $paymentResponse = null;  // Nullable type

    public function __construct(mixed $operation)
    {
        
        $this->setType($operation->type ?? null);
        $this->setStatus($operation->status ?? null);
        $this->setPaySolTransactionId($operation->paySolTransactionId ?? null);
        $this->setPayFrexTransactionId($operation->payFrexTransactionId ?? null);
        $this->setTransactionId($operation->transactionId ?? null);
        $this->setOperationType($operation->operationType ?? null);
        $this->setOperationType($operation->operationType ?? null);
        $this->setDetails($operation->details ?? null);
        $this->setCurrency($operation->currency ?? null);
        $this->setAmount($operation->amount ?? null);
        $this->setRespCode($operation->respCode ?? null);
        $this->setMerchantTransactionId($operation->merchantTransactionId ?? null);
        $this->setService($operation->service ?? null);
        $this->setMessage($operation->message ?? null);
        $this->setPaymentDetails($operation->paymentDetails ?? null);
        $this->setPaymentCode($operation->paymentCode ?? null);
        $this->setPaymentMessage($operation->paymentMessage ?? null);
        $this->setMPI($operation->mpi ?? null);
        $this->setPaymentMethod($operation->paymentMethod ?? null);
        $this->setPaymentSolution($operation->paymentSolution ?? null);
        $this->setAuthCode($operation->paymentSolution ?? null);
        $this->setCheckoutURL($operation->checkoutURL ?? null);
        $this->setSubscriptionPlan($operation->subscriptionPlan ?? null);
        $this->setFee($operation->fee ?? null);
        $this->setCheckFields($operation->checkFields ?? null);
        $this->setStatusType3DS($operation->statusType3DS ?? null);
        $this->setRad($operation->rad ?? null);
        $this->setRadMessage($operation->radMessage ?? null);
        $this->setPaymentResponse($operation->paymentResponse ?? null);
    }

    // Setters
    private function setType(?string $type): void
    {
        $this->type = $type;
    }

    private function setCheckFields(mixed $checkFields): void
    {
        $this->checkFields = $checkFields;
    }

    private function setStatusType3DS(mixed $statusType3DS): void
    {
        $this->statusType3DS = $statusType3DS;
    }

    private function setRad(mixed $rad): void
    {
        $this->rad = $rad;
    }

    private function setRadMessage(?string $radMessage): void
    {
        $this->radMessage = $radMessage;
    }

    private function setPaymentResponse(mixed $paymentResponse): void
    {
        $this->paymentResponse = $paymentResponse;
    }

    private function setCheckoutURL(?string $checkoutURL): void
    {
        $this->checkoutURL = $checkoutURL;
    }

    private function setSubscriptionPlan(mixed $subscriptionPlan): void
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    private function setFee(?float $fee): void
    {
        $this->fee = $fee;
    }

    private function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    private function setPaySolTransactionId(mixed $paySolTransactionId): void
    {
        $this->paySolTransactionId = $paySolTransactionId;
    }

    private function setPayFrexTransactionId(mixed $payFrexTransactionId): void
    {
        $this->payFrexTransactionId = $payFrexTransactionId;
    }

    private function setTransactionId(mixed $transactionId): void
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

    private function setMerchantTransactionId(mixed $merchantTransactionId): void
    {
        $this->merchantTransactionId = $merchantTransactionId;
    }

    private function setAmount(?float $amount): void
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

    private function setService(mixed $service): void
    {
        $this->service = $service;
    }

    private function setPaymentCode(mixed $paymentCode): void
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

    private function setAuthCode(mixed $authCode): void
    {
        $this->authCode = $authCode;
    }

    // Getters
    /**
     * Get the type of the operation.
     *
     * @return string|null The operation type or null if not found.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get the status of the operation.
     *
     * @return string|null The operation status or null if not found.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Get the PaySol transaction ID.
     *
     * @return mixed The PaySol transaction ID or null if not found.
     */
    public function getPaySolTransactionId(): mixed
    {
        return $this->paySolTransactionId;
    }

    /**
     * Get the PayFrex transaction ID.
     *
     * @return mixed The PayFrex transaction ID or null if not found.
     */
    public function getPayFrexTransactionId(): mixed
    {
        return $this->payFrexTransactionId;
    }

    /**
     * Get the transaction ID.
     *
     * @return mixed The transaction ID or null if not found.
     */
    public function getTransactionId(): mixed
    {
        return $this->transactionId;
    }

    /**
     * Get the operation type.
     *
     * @return string|null The operation type or null if not found.
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * Get the currency of the operation.
     *
     * @return string|null The currency or null if not found.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Get the amount of the operation.
     *
     * @return ?float The operation amount.
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Get the service of the operation.
     *
     * @return mixed The service information.
     */
    public function getService(): mixed
    {
        return $this->service;
    }

    /**
     * Get the message associated with the operation.
     *
     * @return string|null The operation message or null if not found.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the merchant transaction ID.
     *
     * @return mixed The merchant transaction ID or null if not found.
     */
    public function getMerchantTransactionId(): mixed
    {
        return $this->merchantTransactionId;
    }

    /**
     * Get the response code object for the operation.
     *
     * @return ?RespCode The response code object.
     */
    public function getRespCode(): ?RespCode
    {
        return $this->respCode;
    }

    /**
     * Get the payment details object for the operation.
     *
     * @return ?PaymentDetails The payment details object.
     */
    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

    /**
     * Get the payment code associated with the operation.
     *
     * @return mixed The payment code or null if not found.
     */
    public function getPaymentCode(): mixed
    {
        return $this->paymentCode;
    }

    /**
     * Get the payment message associated with the operation.
     *
     * @return string|null The payment message or null if not found.
     */
    public function getPaymentMessage(): ?string
    {
        return $this->paymentMessage;
    }

    /**
     * Get the MPI  object for the operation.
     *
     * @return ?MPI The MPI object.
     */
    public function getMPI(): ?MPI
    {
        return $this->mpi;
    }

    /**
     * Get additional details of the operation.
     *
     * @return mixed Additional details or null if not found.
     */
    public function getDetails(): mixed
    {
        return $this->details;
    }

    /**
     * Get the payment method used in the operation.
     *
     * @return mixed The payment method or null if not found.
     */
    public function getPaymentMethod(): mixed
    {
        return $this->paymentMethod;
    }

    /**
     * Get the payment solution used in the operation.
     *
     * @return mixed The payment solution or null if not found.
     */
    public function getPaymentSolution(): mixed
    {
        return $this->paymentSolution;
    }

    /**
     * Get the authorization code associated with the operation.
     *
     * @return mixed The authorization code or null if not found.
     */
    public function getAuthCode(): mixed
    {
        return $this->authCode;
    }

    /**
     * Get the checkout URL for the operation.
     *
     * @return string|null The checkout URL or null if not found.
     */
    public function getCheckoutURL(): ?string
    {
        return $this->checkoutURL;
    }

    /**
     * Get the subscription plan information associated with the operation.
     *
     * @return mixed Subscription plan information or null if not found.
     */
    public function getSubscriptionPlan(): mixed
    {
        return $this->subscriptionPlan;
    }

    /**
     * Get the fee associated with the operation.
     *
     * @return float|null The fee amount or null if not found.
     */
    public function getFee(): ?float
    {
        return $this->fee;
    }

    /**
     * Get the check fields associated with the operation.
     *
     * @return mixed Check fields or null if not found.
     */
    public function getCheckFields(): mixed
    {
        return $this->checkFields;
    }

    /**
     * Get the status of the 3DS (3-D Secure) for the operation.
     *
     * @return mixed The 3DS status or null if not found.
     */
    public function getStatusType3DS(): mixed
    {
        return $this->statusType3DS;
    }

    /**
     * Get RAD (Risk Assessment Decision) information associated with the operation.
     *
     * @return mixed RAD information or null if not found.
     */
    public function getRad(): mixed
    {
        return $this->rad;
    }

    /**
     * Get the RAD message associated with the operation.
     *
     * @return string|null The RAD message or null if not found.
     */
    public function getRadMessage(): ?string
    {
        return $this->radMessage;
    }

    /**
     * Get the payment response information associated with the operation.
     *
     * @return mixed Payment response information or null if not found.
     */
    public function getPaymentResponse(): mixed
    {
        return $this->paymentResponse;
    }



}
