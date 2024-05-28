<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

header('Content-Type: application/json');

try {
       $jsonData = json_decode(file_get_contents('php://input'), true);
       $merchantTransactionID = $jsonData['merchant_transaction_id'] ?? null;
       $transactionId = $jsonData['transaction_id'] ?? null;

       // Configuration of merchant credentials.
       $cred = new Credentials();
       $cred->setMerchantId(MERCHANT_ID)
              ->setMerchantPassword(MERCHANT_PASS)
              ->setProductId(PRODUCT_ID)
              ->setEnvironment(Environment::STAGING);

       // Setting up payment request parameters, including customer and transaction details.       
       $parameters = new Parameters();
       $parameters
              ->setPaymentSolution(PaymentSolutions::CAIXAPUCPUCE)
              ->setMerchantTransactionId($merchantTransactionID)
              ->setTransactionId($transactionId);


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
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendCapturePaymentRequest();
       // Access the status element
       $status = $request->getResponse()->getTransaction()->getOperations()->getPaymentSolutionOperation()->getStatus();
       echo json_encode(['status' => $status, 'raw_response' => $request->getResponse()->getRawResponse()]);
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


