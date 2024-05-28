<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\Category;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\AccommodationTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Config\PaySolExtended\Items\AccommodationItem;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;

// Set the content type of the response to JSON, so the client knows how to parse it
header('Content-Type: application/json');

try {
       $merchantTransactionID = random_int(100000, 999999); // Generate a random integer between 100000 and 999999 to use as a merchant transaction ID if one is not provided
       $jsonData = json_decode(file_get_contents('php://input'), true);  // Retrieve the JSON data sent in the request body and convert it into an associative array
       $amount = $jsonData['amount'] ?? null; // Attempt to retrieve the transaction amount from the JSON data. If not provided, default to null. This amount represents the total funds to be transferred or charged in the transaction.

       // 1. Configuration of merchant credentials.
       $cred = new Credentials();
       $cred->setMerchantId(MERCHANT_ID)
              ->setMerchantPassword(MERCHANT_PASS)
              ->setProductId(PRODUCT_ID_ACCOMMODATION)
              ->setEnvironment(Environment::STAGING);

       // 2. Setting up payment request parameters, including customer and transaction details.      
       $parameters = new QuixParameters();
       $parameters->setAmount($amount)
              ->setCustomerId('4')
              ->setCustomerEmail('test@mail.com')
              ->setMerchantTransactionId($merchantTransactionID)
              ->setIpAddress('192.168.1.1')
              ->setCustomerNationalId('99999999R')
              ->setStatusURL('https://test.com/status')
              ->setSuccessURL('https://test.com/success')
              ->setErrorURL('https://test.com/error')
              ->setCancelURL('https://test.com/cancel')
              ->setAwaitingURL('https://test.com/awaiting')
              ->setFirstName('Nombre')
              ->setDob('01-12-1999')
              ->setLastName('Apellido SegundoApellido');

       // 3. Setting The Quix Parameter      
       $billing = new Billing();
       $billing->setBillingFirstName('Nombre')
              ->setBillingLastName('Apellido SegundoApellido')
              ->setBillingAddress('Nombre de la', '28003', 'Barcelona', 'ESP');      
       $transaction = new AccommodationTransaction();
       $transaction->setProduct('instalments')
              ->setBilling($billing)
              ->setCartCurrency(CurrencyCodes::EUR)
              ->setCartTotalPriceWithTax($amount); 
       $item = new AccommodationItem();
       $item->setName('Item 1')
              ->setCategory(Category::PHYSICAL)
              ->setReference('4912345678904')
              ->setUnitPriceWithTax($amount)
              ->setCheckinDate('2024-10-30T16:00:00+00:00')
              ->setCheckoutDate('2024-11-06T12:00:00+00:00')
              ->setEstablishmentName('Hotel Ejemplo')
              ->setAddress('Nombre de la', '28003', 'Barcelona', 'ESP')
              ->setGuests(3)
              ->setUnits(1)
              ->setTotalPriceWithTax($amount)
              ->setAutoShipping(true); 
       $transaction->addItem($item);
       $parameters->setPaysolExtendedData($transaction);
       
       // 4. Setting Credentials And The Payment Parameters In The SDK
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
       // 5. Sending the payment request to AddonPayments.
       $request = $sdk->sendQuixRedirectionPaymentRequest();

       // 6. Retrieve the Response Redirect URL
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