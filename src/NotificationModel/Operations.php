<?php

namespace AddonPaymentsSDK\NotificationModel;

use AddonPaymentsSDK\NotificationModel\Operations\PaymentSolutionOperation;
use AddonPaymentsSDK\NotificationModel\Operations\TRAOperation;
use AddonPaymentsSDK\NotificationModel\Operations\ThreeDsOperation;

class Operations
{
    private mixed $operations;
    private ?TRAOperation $TRAOperation;
    private ?ThreeDsOperation $ThreeDsOperation;
    private ?PaymentSolutionOperation $PaymenSolutionOperation;


    public function __construct(mixed $operations)
    {
        $this->operations = $operations;
        $this->setTRAOperation();
        $this->setThreeDsOperation();
        $this->setPaymentSolutionOperation();
    }


    /**
     * @psalm-param '3DSv2'|'TRA'|'paymentSol' $serviceName
     */
    private function extractOperation(string $serviceName): mixed
    {


        $operations = $this->operations;

        if (isset($this->operations) && !is_array($this->operations)) {

            if ($serviceName === 'paymentSol') {

                return $this->operations;
            }
        } else {


            if ($serviceName === 'paymentSol') {

                foreach ($operations as $operation) {
                    if (isset($operation->paymentSolution) && !isset($operation->service)) {
                        return $operation;
                    }
                }
            } else {

                foreach ($operations as $operation) {
                    if (isset($operation->service) && trim($operation->service) === $serviceName) {
                        return $operation;
                    }
                }
            }
        }
        return null;
    }

    private function setTRAOperation(): void
    {
        $operation = $this->extractOperation('TRA');
        $this->TRAOperation = new TRAOperation($operation);
    }

    private function setThreeDsOperation(): void
    {
        $operation = $this->extractOperation('3DSv2');
        $this->ThreeDsOperation = new ThreeDsOperation($operation);
    }

    /**
     * @return void
     */
    private function setPaymentSolutionOperation(): void
    {
        $operation = $this->extractOperation('paymentSol');
        if (empty($operation))
            $this->PaymenSolutionOperation = null;
        $this->PaymenSolutionOperation = new PaymentSolutionOperation($operation);
    }

    /**
     * Get the TRA operation data from the notification.
     *
     * @return TRAOperation|null The TRA operation data or null if not found.
     */
    public function getTRAOperation(): ?TraOperation
    {
        return $this->TRAOperation;
    }

    /**
     * Get the 3DS operation data from the notification.
     *
     * @return ThreeDsOperation|null The ThreeDs operation data or null if not found.
     */
    public function getThreeDsOperation(): ?ThreeDsOperation
    {
        return $this->ThreeDsOperation;
    }

    /**
     * Get the Payment Solution operation data from the notification.
     *
     * @return PaymentSolutionOperation|null The Payment Solution operation data or null if not found.
     */
    public function getPaymentSolutionOperation(): ?PaymentSolutionOperation
    {
        return $this->PaymenSolutionOperation;
    }

    /**
     * Get operation size
     *
     * @return int|null The Payment Solution operation data or null if not found.
     */
    public function getOperationSize(): int|null
    {
    
        return count($this->operations);
    }
}
