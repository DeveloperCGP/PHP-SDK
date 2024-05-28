<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

// Set the content type of the response to JSON, so the client knows how to parse it
header('Content-Type: application/json');

try {
       // 1. Configuration of merchant credentials.
       $cred = new Credentials();
       $cred->setMerchantId(MERCHANT_ID)
              ->setMerchantKey(MERCHANT_KEY)
              ->setProductId(PRODUCT_ID_ACCOMMODATION)
              ->setEnvironment(Environment::STAGING);

       // 2. Setting up auth token request parameters, including customer and transaction details.         
       $parameters = new Parameters();
       $parameters->setCurrency(CurrencyCodes::EUR)
              ->setCountry(CountryCodes::ES)
              ->setCustomerId('44')
              ->setAnonymousCustomer(false);

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
       // 4. Sending the auth token request to AddonPayments.
       $request = $sdk->sendQuixJsAuthRequest();

       // 5. Retrieving the Authentication Token
       echo json_encode(['auth_token' => $request->getResponse()->getAuthToken(), 'raw_response' => $request->getResponse()->getRawResponse()]);
 

       // Use the Auth Token to render the JS Payment form 
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
