<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\RecurringTypes;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

// Set the content type of the response to JSON, so the client knows how to parse it
header('Content-Type: application/json');

try {
       $randomMerchantTransactionID = random_int(100000, 999999); // Generate a random integer between 100000 and 999999 to use as a merchant transaction ID if one is not provided
       $jsonData = json_decode(file_get_contents('php://input'), true); // Retrieve the JSON data sent in the request body and convert it into an associative array
       $merchantTransactionID = $jsonData['merchant_transaction_id'] ?? $randomMerchantTransactionID;  // Check if the merchant_transaction_id is provided in the JSON data, use it if present; otherwise, use the random ID generated above
       $chName = $jsonData['chName'] ?? null; // Attempt to retrieve the card holder's name from the JSON data, default to null if not provided
       $cardNumber = $jsonData['cardNumber'] ?? null; // Attempt to retrieve the card number from the JSON data, default to null if not provided
       $cvv = $jsonData['cvv'] ?? null; // Attempt to retrieve the CVV from the JSON data, default to null if not provided
       $expDate = $jsonData['expDate'] ?? null; // Attempt to retrieve the card's expiration date from the JSON data, default to null if not provided

       
       // 1. Configuration of merchant credentials.
       $cred = new Credentials();
       $cred->setMerchantId(MERCHANT_ID)
              ->setMerchantPassword(MERCHANT_PASS)
              ->setProductId(PRODUCT_ID)
              ->setEnvironment(Environment::STAGING);

       // 2. Setting up payment request parameters, including customer and transaction details.       
       $parameters = new Parameters();
       $parameters->setAmount(9)
              ->setCurrency(CurrencyCodes::EUR)
              ->setCountry(CountryCodes::ES)
              ->setCustomerId(4332)
              ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
              ->setMerchantTransactionId($merchantTransactionID)
              ->setPaymentRecurringType(RecurringTypes::NEW_COF)
              ->setOperationType(OperationTypes::DEBIT)
              ->setCardNumber($cardNumber)
              ->setExpDate($expDate)
              ->setCvnNumber($cvv)
              ->setChName($chName)
              ->setStatusURL('https://test.com/status')
              ->setSuccessURL('https://test.com/success')
              ->setErrorURL('https://test.com/error')
              ->setCancelURL('https://test.com/cancel')
              ->setAwaitingURL('https://test.com/awaiting');

       // 3. Setting Credentials And The Payment Parameters In The SDK
       $config = new Configuration($cred, $parameters);
       $sdk = new AddonPaymentsSDK($config);
} catch (TypeError $e) {
       echo 'TypeError: ' . $e->getMessage(), PHP_EOL;
} catch (InvalidFieldException $e) {
       echo 'InvalidFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (MissingFieldException $e) {
       echo 'MissingFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}


try {
       // 4. Sending the payment request to AddonPayments.
       $request = $sdk->sendH2HPaymentRequest();

       // 5. Retrieve the Response Redirect URL
       echo json_encode(['redirect_url' => $request->getResponse()->getRedirectUrl(), 'raw_response' => $request->getResponse()->getRawResponse()]);
       // Redirect users to complete payments. Receive transaction updates (success or errors) via webhook notifications in our SDK.
} catch (InvalidFieldException $e) {
       echo 'InvalidFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (MissingFieldException $e) {
       echo 'MissingFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
       echo 'NetworkException: ' . $e->getMessage(), PHP_EOL;
} catch (ServerErrorException $e) {
       echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorException $e) {
       echo 'ClientErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}

