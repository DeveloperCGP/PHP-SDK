<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Enums\MerchantExemptionsSca;
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
       $jsonData = json_decode(file_get_contents('php://input'), true); // Retrieve the JSON data sent in the request body and convert it into an associative array
       $subscriptionplan = $jsonData['subscriptionplan'] ?? null; // Attempt to retrieve the subscription plan from the JSON data. If not specified, default to null. This could be used to determine the type of subscription the user wants to purchase or change to.
       $cardnumbertoken = $jsonData['cardnumbertoken'] ?? null; // Attempt to retrieve the tokenized version of the card number from the JSON data. If not provided, default to null. Tokenization is a security measure that replaces sensitive data with non-sensitive equivalents, known as tokens, which have no exploitable value.
      
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
              ->setMerchantTransactionId('4545455')
              ->setOperationType(OperationTypes::DEBIT)
              ->setMerchantExemptionsSca(MerchantExemptionsSca::MIT)
              ->setPaymentRecurringType(RecurringTypes::COF)
              ->setCardNumberToken($cardnumbertoken)
              ->setChName('Pablo Navarro')
              ->setSubscriptionPlan($subscriptionplan)
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

       // 5. Retrieve the Response.
       $status = $request->getResponse()->getTransaction()->getOperations()->getPaymentSolutionOperation()->getStatus();
       echo json_encode(['status' => $status, 'raw_response' => $request->getResponse()->getRawResponse()]);
       // Receive transaction updates (success or errors) via webhook notifications in our SDK. 
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


