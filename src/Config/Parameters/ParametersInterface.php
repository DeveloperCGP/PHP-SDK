<?php

namespace AddonPaymentsSDK\Config\Parameters;

use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Validation;
use \DateTime;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\LanguageCodes;
use AddonPaymentsSDK\Config\Enums\Types;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;

/**
 * Abstract class representing the interface for setting parameters required for payment requests.
 * This class provides methods to set various configurations and parameters needed for payment processing.
 */

abstract class ParametersInterface
{

    use LoggerTrait;
    protected array $otherConfigurations = [];
    protected bool $isSettingPaysolExtendedData = false;

    public function __construct()
    {
    }

    // Setters

    /**
     * Sets the merchant transaction ID.
     * 
     * @param string $merchantTransactionId The transaction identifier in the merchant's platform.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided ID is not an integer.
     */
    public function setMerchantTransactionId(string $merchantTransactionId): self
    {
        // Validate the merchant transaction ID (alphanumeric, 1-45 characters)
        if (!preg_match('/^[a-zA-Z0-9]{1,45}$/', $merchantTransactionId)) {
            throw new InvalidFieldException("Merchant Transaction ID must be alphanumeric and between 1 to 45 characters.");
        }

        $this->otherConfigurations['merchantTransactionId'] = $merchantTransactionId;
        return $this;
    }

    /**
     * Sets the amount for the transaction.
     * 
     * @param float $amount The transaction amount.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided amount is not a valid number.
     */

    public function setAmount(float $amount): self
    {
        // Convert the float to a string for preg_match validation
        $amountStr = (string) $amount;
        if (!preg_match('/^\d+(\.\d{1,4})?$/', $amountStr)) {
            throw new InvalidFieldException("Amount must be a number with up to 4 decimal places.");
        }

        if ($amount > 1000000.00 ||  $amount < 0) {
            throw new InvalidFieldException("Amount should be between 0 - 1000000.00.");
        }

        $decimalPlaces = ($temp = strrchr($amountStr, '.')) !== false ? strlen(substr($temp, 1)) : 0;

        // Ensure the number of decimal places does not exceed 4
        $decimalPlaces = min(4, $decimalPlaces);

        // Format the amount with dynamic decimal places
        $formattedAmount = number_format($amount, $decimalPlaces, '.', '');
        // Save the validated and formatted amount
        $this->otherConfigurations['amount'] = $formattedAmount;

        return $this;
    }


    public function getAmount(): float
    {

        return $this->otherConfigurations['amount'];
    }
    public function setProductId(int $productId): self
    {

        $this->otherConfigurations['productId'] = $productId;
        return $this;
    }

    /**
     * Sets the currency code for the transaction.
     * 
     * @param CurrencyCodes $currency The currency code (e.g., 'EUR', 'USD').
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided currency code is not valid.
     */
    public function setCurrency(CurrencyCodes $currency): self
    {
        $this->otherConfigurations['currency'] = $currency->value;
        return $this;
    }

    /**
     * Sets the country code.
     * 
     * @param CountryCodes $country The country code (ISO 3166-1 alpha-2 format).
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided country code is not valid.
     */
    public function setCountry(CountryCodes $country): self
    {
        $this->otherConfigurations['country'] = $country->value;
        return $this;
    }

    /**
     * Sets the Api Version.
     * 
     * @param int $version 
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided country code is not valid.
     */
    public function setApiVersion(int $version): self
    {
        if ($version < 0) {
            $this->logError('value should be possitve number');
            throw new InvalidFieldException('value should be possitve number');
        }
        $this->otherConfigurations['apiVersion'] = $version;
        return $this;
    }

    /**
     * Sets the customer ID.
     * 
     * @param string $customerId The customer identifier in the merchant's platform.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided ID is not an integer.
     */

    public function setCustomerId(string $customerId): self
    {
        // Validate the customer ID (alphanumeric, 1-80 characters)
        if (!preg_match('/^[a-zA-Z0-9]{1,80}$/', $customerId)) {
            throw new InvalidFieldException("Customer ID must be alphanumeric and between 1 to 80 characters.");
        }
        $this->otherConfigurations['customerId'] = $customerId;
        return $this;
    }

    /**
     * Sets the payment solution for the transaction.
     * 
     * @param PaymentSolutions $paymentSolution The payment solution name (e.g., 'creditcards', 'paypal').
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided payment solution is not valid.
     */

    public function setPaymentSolution(PaymentSolutions $paymentSolution): self
    {

        $this->otherConfigurations['paymentSolution'] = $paymentSolution->value;
        return $this;
    }


    /**
     * Sets the language for the payment interface.
     * 
     * @param LanguageCodes $language The language code (e.g., 'EN', 'ES').
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided language code is not valid.
     */
    public function setLanguage(LanguageCodes $language): self
    {

        $this->otherConfigurations['language'] = $language->value;
        return $this;
    }

    /**
     * Sets the transaction type.
     * 
     * @param Types $type The transaction type (e.g., 'ecom', 'moto').
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided type is not valid.
     */
    public function setType(Types $type): self
    {

        $this->otherConfigurations['type'] = $type->value;
        return $this;
    }

    function isUrl(string $string): bool
    {

        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Sets the URL to which the transaction status will be sent.
     * 
     * @param string $statusURL The URL for receiving transaction status updates.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided URL is not valid.
     */
    public function setStatusURL(string $statusURL): self
    {
        if (!$this->isUrl($statusURL)) {
            $this->logError("Status url should be a valid url is required.");
            throw new InvalidFieldException("Status url should be a valid url is required.");
        }
        $this->otherConfigurations['statusURL'] = $statusURL;
        return $this;
    }

    /**
     * Sets the URL for redirecting the user during the payment process if additional actions are required.
     *
     * @param string $awaitingURL The URL for redirecting during awaiting actions.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided URL is not valid.
     */
    public function setAwaitingURL(string $awaitingURL): self
    {
        if (!$this->isUrl($awaitingURL)) {
            $this->logError("Awaiting url should be a valid url is required.");
            throw new InvalidFieldException("Awaiting url should be a valid url is required.");
        }
        $this->otherConfigurations['awaitingURL'] = $awaitingURL;
        return $this;
    }

    /**
     * Sets the URL to which the customer will be redirected after a successful transaction.
     *
     * @param string $successURL The success URL for post-transaction redirection.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided URL is not valid.
     */

    public function setSuccessURL(string $successURL): self
    {
        if (!$this->isUrl($successURL)) {
            $this->logError("Success url should be a valid url is required.");
            throw new InvalidFieldException("Success url should be a valid url is required.");
        }
        $this->otherConfigurations['successURL'] = $successURL;
        return $this;
    }


    /**
     * Sets the URL to which the customer will be redirected after a transaction error.
     *
     * @param string $errorURL The error URL for post-transaction redirection.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided URL is not valid.
     */

    public function setErrorURL(string $errorURL): self
    {
        if (!$this->isUrl($errorURL)) {
            $this->logError("Error url should be a valid url is required.");
            throw new InvalidFieldException("Error url should be a valid url is required.");
        }
        $this->otherConfigurations['errorURL'] = $errorURL;
        return $this;
    }

    /**
     * Sets the URL to which the customer will be redirected if they cancel the transaction.
     *
     * @param string $cancelURL The cancel URL for post-transaction redirection.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided URL is not valid.
     */

    public function setCancelURL(string $cancelURL): self
    {
        if (!$this->isUrl($cancelURL)) {
            $this->logError("Cancel url should be a valid url is required.");
            throw new InvalidFieldException("Cancel url should be a valid url is required.");
        }
        $this->otherConfigurations['cancelURL'] = $cancelURL;
        return $this;
    }





    /**
     * Sets the customer's email address.
     *
     * @param string $customerEmail The email address of the customer.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided email address is not valid.
     */

    public function setCustomerEmail(string $customerEmail): self
    {
        if (!$this->isValidEmail($customerEmail)) {
            $this->logError("Customer Email should be a valid email address and max 100 character.");
            throw new InvalidFieldException("Customer Email should be a valid email address and max 100 character.");
        }
        $this->otherConfigurations['customerEmail'] = $customerEmail;
        return $this;
    }

    /**
     * Sets the national ID of the customer.
     *
     * @param string $customerNationalId The national ID of the customer.
     * @return self Returns the instance of the class for method chaining.
     */

    public function setCustomerNationalId(string $customerNationalId): self
    {
        // Check if the national ID exceeds 100 characters
        if (strlen($customerNationalId) > 100) {
            throw new InvalidFieldException("Customer National ID must not exceed 100 characters.");
        }

        // Check if the national ID is alphanumeric
        if (!preg_match('/^[a-zA-Z0-9]+$/', $customerNationalId)) {
            throw new InvalidFieldException("Customer National ID must be alphanumeric.");
        }

        // Additional validation for Spanish DNI (8 digits followed by a letter)
        if (preg_match('/^[0-9]{8}[A-Za-z]$/', $customerNationalId) === 1) {
            if (!$this->isValidSpanishDNI($customerNationalId)) {
                throw new InvalidFieldException("Invalid Spanish DNI.");
            }
        }

        $this->otherConfigurations['customerNationalId'] = $customerNationalId;
        return $this;
    }

    /**
     * Sets the country code of the customer.
     *
     * @param CountryCodes $customerCountry The ISO country code of the customer.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided country code is not valid.
     */
    public function setCustomerCountry(CountryCodes $customerCountry): self
    {
        $this->otherConfigurations['customerCountry'] = $customerCountry;
        return $this;
    }

    /**
     * Sets the date of birth of the customer.
     *
     * @param string $dob The date of birth in the format d-m-Y.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided date is not in the valid format.
     */
    public function setDob(string $dob): self
    {
        if (!$this->isValidDateTime($dob)) {
            $this->logError("dob should be a valid in valid format d-m-Y");
            throw new InvalidFieldException("dob should be a valid in valid format d-m-Y");
        }
        $this->otherConfigurations['dob'] = $dob;
        return $this;
    }

    /**
     * Sets the first name of the customer.
     *
     * @param string $firstName The first name of the customer.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setFirstName(string $firstName): self
    {
        if (strlen($firstName) > 100) {
            throw new InvalidFieldException("First Name must not exceed 100 characters.");
        }

        $this->otherConfigurations['firstName'] = $firstName;
        return $this;
    }

    /**
     * Sets the last name of the customer.
     *
     * @param string $lastName The last name of the customer.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setLastName(string $lastName): self
    {
        if (strlen($lastName) > 100) {
            throw new InvalidFieldException("Last Name must not exceed 100 characters.");
        }
        $this->otherConfigurations['lastName'] = $lastName;
        return $this;
    }




    /**
     * Sets the card holder's name.
     *
     * @param string $chName The name of the card holder.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setChName(string $chName): self
    {
        if (strlen($chName) > 100) {
            throw new InvalidFieldException("Ch Name must not exceed 100 characters.");
        }
        $this->otherConfigurations['chName'] = $chName;
        return $this;
    }

    /**
     * Sets the Reference Id.
     *
     * @param string $referenceId Variable reference for businesses that need FB500 reconciliation files..
     * @return self Returns the instance of the class for method chaining.
     */
    public function setReferenceId(string $referenceId): self
    {
        if (strlen($referenceId) > 12) {
            throw new InvalidFieldException("Reference Id must not exceed 100 characters.");
        }
        $this->otherConfigurations['referenceId'] = $referenceId;
        return $this;
    }

    /**
     * Sets Description.
     *
     * @param string $referenceId Description of the transaction.
     * Preference on the type of 3DSv2 authentication to request from the client.
     * It does not affect the transaction, it serves so that you can make a better identification.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setDescription(string $description): self
    {
        if (strlen($description) > 1000) {
            throw new InvalidFieldException("Description must not exceed 1000 characters.");
        }
        $this->otherConfigurations['description'] = $description;
        return $this;
    }

    /**
     * Sets Challenge Ind.
     *
     * @param string $referenceId Preference on the type of 3DSv2 authentication to request from the client.
     * @return self Returns the instance of the class for method chaining.
     */

    public function setChallengeInd(string $challengeInd): self
    {

        $acceptedValues = [
            '01' => 'no preference',
            '02' => 'avoid challenge',
            '03' => 'require challenge (merchant preference)',
            '04' => 'require challenge (mandatory)',
            '05' => 'avoid challenge (transaction risk analysis performed)',
            '06' => 'avoid challenge (only in sharing data)',
            '07' => 'avoid challenge (SCA already carried out)',
            '08' => 'avoid challenge (use whitelist exemption)',
            '09' => 'avoid challenge (request whitelist)',
        ];

        if (array_key_exists($challengeInd, $acceptedValues)) {
            $this->otherConfigurations['challengeInd'] = $challengeInd;
        } else {
            // Handle the error - either throw an exception, log a message, etc.
            $acceptedValuesList = implode(", ", array_map(function ($key, $val) {
                return "$key: $val";
            }, array_keys($acceptedValues), $acceptedValues));
            $this->logError("Invalid Challenge Ind value. Accepted values are: $acceptedValuesList");
            throw new InvalidFieldException("Invalid Challenge Ind value. Accepted values are: $acceptedValuesList");
        }
        return $this;
    }


    /**
     * Sets Show Remember Me.
     *
     * @param bool $showRememberMe if the value is true the remember me box will be visible to the user if false it will be hidden (true by default ).
     * @return self Returns the instance of the class for method chaining.
     */

    public function setShowRememberMe(bool $showRememberMe): self
    {
        $this->otherConfigurations['showRememberMe'] = $showRememberMe;
        return $this;
    }


    /**
     * Sets Merchant Params.
     *
     * @param array $params Parameters that are sent to modify the configuration of the trade or the processing of a transaction.
     * They are received back inside the label
     * Does not support special characters.
     * @return self Returns the instance of the class for method chaining.
     */

    public function setMerchantParams(array $params)
    {
        // Convert the associative array to the "key:value" format
        $keyValuePairs = array_map(function ($key, $value) {
            return "$key:$value";
        }, array_keys($params), $params);

        // Join the pairs with semicolons
        $paramsString = implode(';', $keyValuePairs);

        // Check if the string exceeds 500 characters
        if (strlen($paramsString) > Validation::MERCHANT_PARAMS_MAX) {
            $this->logError("The merchant parameters exceed the maximum length of 500 characters.");
            throw new InvalidFieldException("The merchant parameters exceed the maximum length of 500 characters.");
        }

        $this->otherConfigurations['merchantParams'] = $paramsString;
        return $this;
    }


    /**
     * Sets the transaction ID.
     *
     * @param int $transactionId The transaction ID.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the transaction ID is not an integer.
     */
    public function setTransactionId(int $transactionId): self
    {
        $transactionIdStr = (string)$transactionId;

        // Check if the transaction ID is a whole number and does not exceed 100 digits
        if (!ctype_digit($transactionIdStr) || strlen($transactionIdStr) > 100) {
            throw new InvalidFieldException("Transaction ID must be a whole number with a maximum of 100 digits.");
        }
        $this->otherConfigurations['transactionId'] = $transactionId;
        return $this;
    }

    /**
     * Sets the force token request flag.
     *
     * @param mixed $forceTokenRequest The force token request flag, should be true or false.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided value is not a valid boolean representation.
     */
    public function setForceTokenRequest(bool $forceTokenRequest): self
    {
        $this->otherConfigurations['forceTokenRequest'] = $forceTokenRequest;
        return $this;
    }

    /**
     * Sets the anonymous customer flag.
     *
     * @param mixed $anonymousCustomer The anonymous customer flag, should be 'true' or 'false' as a string.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided value is not a valid boolean representation.
     */
    public function setAnonymousCustomer(bool $anonymousCustomer): self
    {
        $this->otherConfigurations['anonymousCustomer'] = $anonymousCustomer;
        return $this;
    }


    /**
     * Sets the prepayment token.
     *
     * @param string $prepayToken The prepayment token to be used for the transaction.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setPrepayToken(string $prepayToken): self
    {
        if (!preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $prepayToken)) {
            throw new InvalidFieldException("Merchant Key must be in UUID format.");
        }
        $this->otherConfigurations['prepayToken'] = $prepayToken;
        return $this;
    }


    /**
     * Sets additional parameters for the transaction.
     *
     * @param string $key The key for the additional parameter.
     * @param mixed $value The value of the additional parameter.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setAdditionalParameters(string $key, mixed $value): self
    {
        $this->otherConfigurations[$key] = $value;
        return $this;
    }

    /**
     * Adds a header to accept JSON responses.
     *
     * @param bool $json A boolean flag to indicate if JSON responses are to be accepted.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addAcceptJsonHeader(bool $json): self
    {
        $this->otherConfigurations['header'] = $json;
        return $this;
    }


    /**
     * Retrieves all other configurations set for the transaction.
     *
     * @return array An associative array of all configurations.
     */
    public function getOtherConfigurations(): array
    {
        return $this->otherConfigurations;
    }


    /**
     * Validates if the provided string is a valid date in the specified format.
     *
     * @param string $value The date string to be validated.
     * @return bool Returns true if the date is valid, false otherwise.
     */
    private function isValidDateTime(string $value): bool
    {
        $format = 'd-m-Y'; // Format to match the provided example
        $dateTime = DateTime::createFromFormat($format, $value);

        return $dateTime && $dateTime->format($format) === $value;
    }

    /**
     * Checks if the provided value is an integer or a float.
     *
     * @param int|float $value The value to check.
     * @return bool Returns true if the value is an integer or a float, false otherwise.
     */
    private function checkInteger(int|float $value): bool
    {
        $type = gettype($value);

        if ($type === 'integer' || $type === 'double') {
            if ($value <= 0) {
                $this->logError("Amount should be bigger than 0");
                throw new InvalidFieldException("Amount should be bigger than 0");
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates if the provided string is a valid email address.
     *
     * @param string $email The email address to validate.
     * @return bool Returns true if the email address is valid, false otherwise.
     */
    function isValidEmail(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        // Check if email length exceeds 100 characters
        if (strlen($email) > 100) {
            return false;
        }

        return true;
    }

    /**
     * Validates a Spanish DNI.
     *
     * @param string $dni The DNI to validate.
     * @return bool Returns true if the DNI is valid, false otherwise.
     */
    private function isValidSpanishDNI(string $dni): bool
    {
        $dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        $number = (int) substr($dni, 0, 8); // Cast to integer
        $letter = strtoupper(substr($dni, -1));

        return $dniLetters[$number % 23] === $letter;
    }



    /**
     * Validates if the provided string represents a boolean value.
     *
     * @param string $value The string to check.
     * @return bool Returns true if the string is 'true' or 'false', false otherwise.
     */
    private function isValidBoolean(string $value): bool
    {


        // Check for string representations of true or false
        $lowerValue = strtolower(trim($value));
        if (in_array($lowerValue, ['true', 'false'])) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves Api Version
     *
     * @return int of Api Version.
     */
    private function getApiVersion(): int
    {
        return $this->otherConfigurations['apiVersion'];
    }

    /**
     * @return void
     */
    public function validate()
    {
    }
}
