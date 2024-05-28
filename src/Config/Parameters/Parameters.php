<?php

namespace AddonPaymentsSDK\Config\Parameters;


use AddonPaymentsSDK\Config\Enums\MerchantExemptionsSca;
use AddonPaymentsSDK\Config\Parameters\ParametersInterface;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\RecurringTypes;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;

class Parameters extends ParametersInterface
{

    /**
     * Sets the type of recurring payment.
     *
     * @param RecurringTypes $paymentRecurringType The type of recurring payment (e.g., subscription, installment).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided recurring type is not valid.
     */
    public function setPaymentRecurringType(RecurringTypes $paymentRecurringType): self
    {

        $this->otherConfigurations['paymentRecurringType'] = $paymentRecurringType->value;
        return $this;
    }

    

    /**
     * Sets the card number for the transaction.
     *
     * @param string $cardNumber The card number.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCardNumber(string $cardNumber): self
    {
        if (!ctype_digit($cardNumber) || !$this->is_valid_luhn($cardNumber)) {
            throw new InvalidFieldException("Card Number should pass Luhn check.");
        }
        $this->otherConfigurations['cardNumber'] = $cardNumber;
        return $this;
    }

    /**
     * Sets the auto capture flag for the transaction.
     *
     * @param mixed $autoCapture The auto capture flag (true or false).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided value is not a valid boolean.
     */
    public function setAutoCapture(bool $autoCapture): self
    {
        if (!is_bool($autoCapture)) {
            throw new InvalidFieldException("The provided value is not a valid boolean.");
        }
        $this->otherConfigurations['autoCapture'] = $autoCapture ? 'true' : 'false';
        return $this;
    }

    /**
     * Sets the flag to print a receipt for the transaction.
     *
     * @param mixed $printReceipt The print receipt flag (true or false).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided value is not a valid boolean.
     */
    public function setPrintReceipt(bool $printReceipt): self
    {

        $this->otherConfigurations['printReceipt'] = $printReceipt;

        return $this;
    }

    /**
     * Sets the expiration date of the card.
     *
     * @param string $expDate The expiration date in MMYY format.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided expiration date is not in valid MMYY format.
     */
    public function setExpDate(string $expDate): self
    {
        if (!$this->isValidMMYY($expDate)) {
            throw new InvalidFieldException("Expire Date should be MMYY.");
        }

        $this->otherConfigurations['expDate'] = $expDate;
        return $this;
    }

    /**
     * Sets the CVN number of the card.
     *
     * @param int $cvnNumber The CVN number.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided CVN number is not an integer.
     */
    public function setCvnNumber(int $cvnNumber): self
    {
        if (!preg_match('/^\d{3,4}$/', (string) $cvnNumber)) {
            throw new InvalidFieldException("CVN number must be numerical and contain 3 to 4 digits.");
        }
        $this->otherConfigurations['cvnNumber'] = $cvnNumber;
        return $this;
    }

    /**
     * Sets the tokenized card number.
     *
     * @param string $cardNumberToken The tokenized card number.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided token is not valid.
     */

    public function setCardNumberToken(string $cardNumberToken): self
    {
        if (!preg_match('/^[a-zA-Z0-9]{16,20}$/', $cardNumberToken)) {
            throw new InvalidFieldException("Card number token must be alphanumeric and contain 16 to 20 characters.");
        }
        $this->otherConfigurations['cardNumberToken'] = $cardNumberToken;
        return $this;
    }

    /**
     * Sets the subscription plan.
     *
     * @param string $subscriptionPlan The subscription plan.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the subscription plan is not valid.
     */
    public function setSubscriptionPlan(string $subscriptionPlan): self
    {
        // Check if the subscription plan is alphanumeric and exactly 45 characters
        if (!preg_match('/^[a-zA-Z0-9]{1,45}$/', $subscriptionPlan)) {
            throw new InvalidFieldException("Subscription plan must be alphanumeric and exactly 45 characters.");
        }

        $this->otherConfigurations['subscriptionPlan'] = $subscriptionPlan;
        return $this;
    }

    /**
     * Sets the type of operation for the transaction.
     *
     * @param OperationTypes $operationType The operation type (e.g., DEBIT, CREDIT).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided operation type is not valid.
     */
    public function setOperationType(OperationTypes $operationType): self
    {

        $this->otherConfigurations['operationType'] = $operationType->value;
        return $this;
    }


    /**
     * Sets the type of operation for the transaction.
     *
     * @param MerchantExemptionsSca $operationType The operation type (e.g., DEBIT, CREDIT).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided operation type is not valid.
     */
    public function setMerchantExemptionsSca(MerchantExemptionsSca $merchantExemptionsSca): self
    {

        $this->otherConfigurations['merchantExemptionsSca'] = $merchantExemptionsSca->value;
        return $this;
    }

    private function checkInteger(mixed $value): bool
    {
        $type = gettype($value);

        if ($type === 'integer' || $type === 'double') {
            return true;
        } else {
            return false;
        }
    }

    private function isValidMMYY(string $str): bool
    {
        // Check if the format is MMYY using regex
        if (preg_match('/^(0[1-9]|1[0-2])([0-9]{2})$/', $str, $matches)) {
            // Extract month and year
            $month = $matches[1];
            $year = $matches[2];

            // Check if the extracted values are numeric
            if (is_numeric($month) && is_numeric($year)) {
                // Convert month and year to integers
                $month = intval($month);
                $year = intval($year) + 2000;

                // Check if the date is valid
                return checkdate($month, 1, $year);
            }
        }

        return false;
    }

    private function is_valid_luhn(string $number): bool
    {
        $sum = 0;
        $numDigits = strlen($number);
        $parity = $numDigits % 2;
    
        for ($i = 0; $i < $numDigits; $i++) {
            $digit = (int)$number[$i];
    
            if ($i % 2 == $parity) {
                $digit *= 2;
            }
    
            if ($digit > 9) {
                $digit -= 9;
            }
    
            $sum += $digit;
        }
    
        return ($sum % 10) == 0;
    }



    private function isValidBoolean(string $value): bool
    {


        // Check for string representations of true or false
        $lowerValue = strtolower(trim($value));
        if (in_array($lowerValue, ['true', 'false'])) {
            return true;
        }

        return false;
    }
}
