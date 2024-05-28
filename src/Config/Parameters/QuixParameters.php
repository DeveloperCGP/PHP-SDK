<?php

namespace AddonPaymentsSDK\Config\Parameters;

use AddonPaymentsSDK\Config\Enums\CustomerNationalIdTypes;
use AddonPaymentsSDK\Config\PaySolExtended\BaseTransaction;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
class QuixParameters extends ParametersInterface
{
    use LoggerTrait;
    private ?BaseTransaction $paysolExtendedData = null;
    private float $calculateTotalItemPrice = 0;
    public function __construct()
    {
        $this->otherConfigurations['paymentSolution'] = 'quix';
        $this->otherConfigurations['language'] = 'ES';
        $this->otherConfigurations['currency'] = 'EUR';
        $this->otherConfigurations['country'] = 'ES';
        $this->otherConfigurations['customerCountry'] = 'ES';
    }

    // Setters
    /**
     * Sets the customer's national ID type.
     * Validates the ID type against predefined types.
     * @param CustomerNationalIdTypes $customerNationalIdType Type of the customer's national ID.
     * @return self
     * @throws InvalidFieldException If the ID type is invalid.
     */
    public function setCustomerNationalIdType(CustomerNationalIdTypes $customerNationalIdType): self
    {
        
        $this->otherConfigurations['customerNationalIdType'] = $customerNationalIdType->value;
        return $this;
    }

    /**
     * Sets the IP address of the customer.
     * @param string $ipAddress Customer's IP address.
     * @return self
     */
    public function setIpAddress(string $ipAddress): self
    {
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            throw new InvalidFieldException("Invalid IP address.");
        }
        $this->otherConfigurations['ipAddress'] = $ipAddress;
        return $this;
    }

    /**
     * Sets extended payment solution data for a transaction.
     * Validates and calculates the total item price.
     * Encodes the transaction data into JSON format.
     * @param BaseTransaction $paysolExtendedData Extended data for the transaction.
     * @return self
     */
    public function setPaysolExtendedData(BaseTransaction $paysolExtendedData): self
    {
        $paysolExtendedData->validate();
        $this->paysolExtendedData = $paysolExtendedData;
        if ($paysolExtendedData->calculateTotalItemPrice()) {
            $this->calculateTotalItemPrice = $paysolExtendedData->calculateTotalItemPrice();
        }
        $encodeToJson = json_encode($paysolExtendedData->getData());
        $this->otherConfigurations['paysolExtendedData'] = html_entity_decode(stripslashes($encodeToJson));

        return $this;
    }




    /**
     * Validates the configuration parameters.
     * Checks for the presence of required parameters and consistency of total amount.
     * @return void
     * @throws InvalidFieldException If any required parameter is missing or inconsistent.
     */
    public function validate()
    {
        parent::validate();


        $requiredKeys = [
            'currency' => 'currency.',
            'country' => 'country.',
            'customerId' => 'customerId.',
            'dob' => 'dob.',
            'firstName' => 'firstName.',
            'lastName' => 'lastName.',
            'paysolExtendedData' => 'paysolExtendedData.',
            'customerEmail' => 'customerEmail.',
            'statusURL' => 'statusURL.',
            'successURL' => 'successURL.',
            'errorURL' => 'errorURL.',
            'cancelURL' => 'cancelURL.',
            'awaitingURL' => 'awaitingURL.',
           
        ];


        $missingKeys = [];
    
        foreach ($requiredKeys as $key => $label) {
            if (!isset($this->otherConfigurations[$key]) || $this->otherConfigurations[$key] == '') {
                $missingKeys[] = $label;
            }
        }
        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys));
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys));
        }



        if (!isset($this->otherConfigurations['amount'])) {
            $this->setAmount($this->calculateTotalItemPrice);
        }

        if (isset($this->otherConfigurations['amount']) && $this->paysolExtendedData !== null && $this->otherConfigurations['amount'] != $this->paysolExtendedData->getCartTotalPriceWithTax()) {
            $this->logError("The total price of items does not match the transaction amount.");
            throw new InvalidFieldException("The total price of items does not match the transaction amount.");
        }

        if (isset($this->otherConfigurations['amount']) && ($this->otherConfigurations['amount'] < 50 || $this->otherConfigurations['amount'] > 1200)) {
            $this->logError('Amount shoud be between 50 - 1200');
            throw new InvalidFieldException('Amount shoud be between 50 - 1200');
        }

    }




}
