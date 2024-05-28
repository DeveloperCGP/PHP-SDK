<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
// Set the content type of the response to JSON, so the client knows how to parse it
header('Content-Type: application/json');
try {
       $jsonData = json_decode(file_get_contents('php://input'), true); // Retrieve the JSON data sent in the request body and convert it into an associative array
       $prepayToken = $jsonData['prepayToken'] ?? null;  // Attempt to retrieve the prepay token from the JSON data. If not provided, default to null. This used to authenticate a payment transaction.
       $amount = $jsonData['amount'] ?? null; // Attempt to retrieve the transaction amount from the JSON data. If not provided, default to null. This amount represents the total funds to be transferred or charged in the transaction.

       // 1. Configuration of merchant credentials.
       $cred = new Credentials(); 
       $cred->setMerchantId(MERCHANT_ID)
              ->setMerchantKey(MERCHANT_KEY)
              ->setProductId(PRODUCT_ID)
              ->setEnvironment(Environment::STAGING);

       // 2. Setting up payment request parameters, including customer and transaction details.        
       $parameters = new Parameters();
       $parameters->setAmount($amount)
              ->setMerchantTransactionId('1496918')
              ->setPrepayToken($prepayToken) // Get the Prepay Token from the Rendered Payment Form 
              ->setCurrency(CurrencyCodes::EUR)
              ->setCountry(CountryCodes::ES)
              ->setCustomerId('322111')
              ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
              ->setOperationType(OperationTypes::DEBIT)
              ->setApiVersion(5)
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
       $request = $sdk->sendJsChargeRequest();

       // 5. Retrieving the Redirect URL.
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