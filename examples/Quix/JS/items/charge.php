<?php
include __DIR__ . '/../../../../vendor/autoload.php';
include __DIR__ . '/../../../credentials.php';

use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\Category;
use AddonPaymentsSDK\Config\PaySolExtended\ItemTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
// Set the content type of the response to JSON, so the client knows how to parse it
header('Content-Type: application/json');

try {
       $merchantTransactionID = random_int(100000, 999999); // Generate a random integer between 100000 and 999999 to use as a merchant transaction ID if one is not provided
       $jsonData = json_decode(file_get_contents('php://input'), true); // Retrieve the JSON data sent in the request body and convert it into an associative array
       $prepayToken = $jsonData['prepayToken'] ?? null;  // Attempt to retrieve the prepay token from the JSON data. If not provided, default to null. This used to authenticate a payment transaction.
       $amount = $jsonData['amount'] ?? null; // Attempt to retrieve the transaction amount from the JSON data. If not provided, default to null. This amount represents the total funds to be transferred or charged in the transaction.


       // 1. Configuration of merchant credentials.
       $cred = new Credentials();
       $cred->setMerchantId(MERCHANT_ID)
              ->setProductId(PRODUCT_ID_ITEMS)
              ->setEnvironment(Environment::STAGING);

       // 2. Setting up payment request parameters, including customer and transaction details.             
       $parameters = new QuixParameters();
       $parameters->setAmount($amount)
              ->setCustomerId('44')
              ->setPrepayToken($prepayToken) // Get the Prepay Token from the Rendered Payment Form 
              ->setCustomerEmail('test@mail.com')
              ->setMerchantTransactionId($merchantTransactionID)
              ->setCustomerNationalId('99999999R')
              ->setIpAddress('192.168.1.1')
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
       $transaction = new ItemTransaction();
       $transaction->setProduct('instalments')
              ->setBilling($billing)
              ->setCartCurrency(CurrencyCodes::EUR)
              ->setCartTotalPriceWithTax($amount);
       $item = new ProductItem();
       $item->setName('Item 1')
              ->setCategory(Category::PHYSICAL)
              ->setReference('4912345678904')
              ->setUnitPriceWithTax($amount)
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
       $request = $sdk->sendQuixJsChargeRequest();

       // 6. Retrieve the Nemuru auth token and Nemuru cart hash
       echo json_encode([
              'nemuru_auth_token' => $request->getResponse()->getNemuruAuthToken(),
              'nemuru_cart_hash' => $request->getResponse()->getNemuruCartHash(),
              'raw_response' => $request->getResponse()->getRawResponse()
       ]);
       // Use nemuruAuthToken & nemuruCartHash to render quix popup to complete user payment process        
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
