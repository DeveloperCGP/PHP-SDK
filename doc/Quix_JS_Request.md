
# Quix JavaScript

## Table of Contents

- [Namespace Import](#namespace-import)
- [Request 01: JavaScript Authentication Request](#request-01-javascript-authentication-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object)
  - [Step 2: Setting Payment Parameters](#step-2-setting-payment-parameters)
  - [Step 3: Setting Credentials And The Payment Parameters In The SDK](#step-3-setting-credentials-and-the-payment-parameters-in-the-sdk)
  - [Step 4: Sending the Authentication Request](#step-4-sending-the-authentication-request)
  - [Step 5: Retrieving the Authentication Token](#step-5-retrieving-the-authentication-token)
- [Request 02: Charge Quix Accommodation Request](#request-02-charge-quix-accommodation-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object-1)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-1)
  - [Step 5: Sending The Charge Request](#step-5-sending-the-charge-request)
  - [Step 6: Getting The NemuruAuthToken & NemuruCartHash](#step-6-getting-the-nemuruauth-token-nemuru-cart-hash)
- [Request 03: Charge Quix Flight Request](#request-03-charge-quix-flight-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object-2)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-1)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-1)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-2)
  - [Step 5: Sending The Charge Request](#step-5-sending-the-charge-request-1)
  - [Step 6: Getting The NemuruAuthToken & NemuruCartHash](#step-6-getting-the-nemuruauth-token-nemuru-cart-hash-1)
- [Request 04: Charge Quix Items Request](#request-04-charge-quix-items-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object-3)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-2)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-2)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-3)
  - [Step 5: Sending The Charge Request](#step-5-sending-the-charge-request-2)
  - [Step 6: Getting The NemuruAuthToken & NemuruCartHash](#step-6-getting-the-nemuruauth-token-nemuru-cart-hash-2)
- [Request 05: Charge Quix Service](#request-05-charge-quix-service)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object-4)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-3)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-3)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-4)
  - [Step 5: Sending The Charge Request](#step-5-sending-the-charge-request-3)
  - [Step 6: Getting The NemuruAuthToken & NemuruCartHash](#step-6-getting-the-nemuruauth-token-nemuru-cart-hash-3)
- [How to Use JS Provided Examples](#how-to-use-js-provided-examples)

## Namespace Import

Before you can utilize the SDK for Quix JavaScript transactions, it's crucial to import the necessary namespaces. This step ensures that your code has access to all the required classes and methods provided by the SDK. Here's how you can import these namespaces in your project:

```php
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\Types;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\AccommodationTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Items\AccommodationItem;
use AddonPaymentsSDK\Config\PaySolExtended\FlightTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Items\FlightItem;
use AddonPaymentsSDK\Config\PaySolExtended\Items\Utils\Passenger;
use AddonPaymentsSDK\Config\PaySolExtended\Items\Utils\Segment;
use AddonPaymentsSDK\Config\PaySolExtended\ItemTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;
use AddonPaymentsSDK\Config\PaySolExtended\ServiceTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ServiceItem;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
```

## Request 01: JavaScript Authentication Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests.

The important credentials that need to be set are:

- `setMerchantId`
  - Identifier of your business on the Addon Payments platform.
- `setMerchantKey`
  - It is the JavaScript password. It is used to verify that the request is legitimate. For the staging environment, it is sent in the welcome email; in production environments, it is retrieved through the BackOffice.
- `setProductId`
  - Product identifier created for your business by AddonPayments for which the transaction must be processed.
- `setEnvironment`
  - Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
     ->setMerchantKey(MERCHANT_KEY)
     ->setProductId(PRODUCT_ID)
     ->setEnvironment(Environment::STAGING);
```

### Step 2: Setting Payment Parameters

In this step, we will provide the SDK with the payment parameters:

- Amount
- Currency
- Country
- Customer Id

```php
$parameters = new Parameters();
$parameters->setCurrency(CurrencyCodes::EUR)
          ->setCountry(CountryCodes::ES)
          ->setCustomerId('44')
          ->setAnonymousCustomer('true');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 4: Sending the Authentication Request

```php
try {
       $request = $sdk->sendQuixJsAuthRequest();
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
```

This function can return multiple exceptions based on specific errors:

- **FieldException**: A parent exception for field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of

 client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Retrieving the Authentication Token

After successfully sending the authentication request, retrieve the authentication token from the response.

```php
$request->getResponse()->getAuthToken();
```

## Request 02: Charge Quix Accommodation Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests.

The important credentials that need to be set are:

- `setMerchantId`
  - Identifier of your business on the Addon Payments platform.
- `setProductId`
  - Product identifier created for your business by AddonPayments for which the transaction must be processed.
- `setEnvironment`
  - Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
        ->setProductId(PRODUCT_ID_ACCOMMODATION)
        ->setEnvironment(Environment::STAGING);
```

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Prepay Token
- Customer Id
- Customer Email
- Merchant Transaction Id
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL
- First Name
- Last Name
- Product Id
- Date Of Birth

```php
$parameters = new Parameters();
$parameters->setAmount(300.00)
      ->setCustomerId('4')
      ->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42')
      ->setCustomerEmail('email@micomercio.com')
      ->setMerchantTransactionId('1321')
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/status')
      ->setErrorURL('https://test.com/status')
      ->setCancelURL('https://test.com/status')
      ->setAwaitingURL('https://test.com/awaiting')
      ->setFirstName('Nombre')
      ->setLastName('Apellido')
      ->setProductId('1234567890')
      ->setDob('01-12-1999');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting The Quix Parameter

In this step, we will add the parameters specifically needed for the Quix accommodation:

- Product Type
- Cart Currency
- Cart Total Price With Tax
- Disable Form Edition: An optional parameter to disable editing the already sent data in the request for the customer
- Billing
  - Billing First Name
  - Billing Last Name
  - Billing Address
- Items
  - Name
  - Category
  - Reference
  - Unit Price With Tax
  - Check In Date
  - Check Out Date
  - Establishment Name
  - Address
  - Guests
  - Units
  - Total Price With Taxes
  - Auto Shipping

```php
$billing = new Billing();
$billing->setBillingFirstName('Nombre')
      ->setBillingLastName('Apellido Om')
      ->setBillingAddress('Nombre de la', '08003', 'Barcelona', 'ESP');      
$transaction = new AccommodationTransaction();
$transaction->setProduct('instalments')
      ->setBilling($billing)
      ->setCartCurrency(CurrencyCodes::EUR)
      ->setCartTotalPriceWithTax(300.00)
      ->setDisableFormEdition(true);   
$item = new AccommodationItem();
$item->setName('Item 1')
      ->setCategory('physical')
      ->setReference('4912345678904')
      ->setUnitPriceWithTax(300.00)
      ->setCheckinDate('2024-10-30T16:00:00+00:00')
      ->setCheckoutDate('2024-11-06T12:00:00+00:00')
      ->setEstablishmentName('Hotel Ejemplo')
      ->setAddress('Nombre de la', '08003', 'Barcelona', 'ESP')
      ->setGuests(3)
      ->setUnits(1)
      ->setTotalPriceWithTax(300.00)
      ->setAutoShipping(true); 
$transaction->addItem($item);
$parameters->setPaysolExtendedData($transaction);
```

### Step 4: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 5: Sending The Charge Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixJsChargeRequest();
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
```

This function returns multiple exceptions based on specific errors:

- **FieldException**: A parent exception for field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 6: Getting The NemuruAuthToken & NemuruCartHash

```php
echo 'nemuruAuthToken: ' . $request->getNemuruAuthToken();
echo 'nemuruCartHash: ' . $request->getNemuruCartHash();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Request 03: Charge Quix Flight Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests.

The important credentials that need to be set are:

- `setMerchantId`
  - Identifier of your business on the Addon Payments platform.
- `setProductId`
  - Product identifier created for your business by AddonPayments for which the transaction must be processed.
- `setEnvironment`
  - Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
        ->setProductId(PRODUCT_ID_ACCOMMODATION)
        ->setEnvironment(Environment::STAGING);
```

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Prepay Token
- Customer Id
- Customer Email
- Merchant Transaction Id
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL
- First Name
- Last Name
- Product Id
- Date Of Birth

```php
$parameters = new Parameters();
$parameters->setAmount(300.00)
      ->setCustomerId('4')
      ->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42')
      ->setCustomerEmail('email@micomercio.com')
      ->setMerchantTransactionId('1321')
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/status')
      ->setErrorURL('https://test.com/status')
      ->setCancelURL('https://test.com/status')
      ->setAwaitingURL('https://test.com/awaiting')
      ->setFirstName('Nombre')
      ->setLastName('Apellido')
      ->setProductId('1234567890')
      ->setDob('01-12-1999');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting The Quix Parameter

In this step, we will add the parameters specifically needed for the Quix flight:

- Product Type
- Cart Currency
- Cart Total Price With Tax
- Disable Form Edition: An optional parameter to disable editing the already sent data in the

 request for the customer
- Billing
  - Billing First Name
  - Billing Last Name
  - Billing Address
- Items
  - Name
  - Category
  - Reference
  - Unit Price With Tax
  - Departure Date
  - Passenger
    - First Name
    - Last Name
  - Segment
    - Iata Departure Code
    - Iata Destination Code
  - Units
  - Total Price With Taxes
  - Auto Shipping

```php
$billing = new Billing();
$billing->setBillingFirstName('Nombre')
      ->setBillingLastName('Apellido Om')
      ->setBillingAddress('Nombre de la', '08003', 'Barcelona', 'ESP');   
$transaction = new FlightTransaction();
$transaction->setProduct('instalments')
      ->setBilling($billing)
      ->setCartCurrency(CurrencyCodes::EUR)
      ->setCartTotalPriceWithTax(300.00)
      ->setDisableFormEdition(true);    
$passenger = new Passenger();
$passenger->setFirstName('Nombre1');
$passenger->setLastName('Apellido1');  
$segment = new Segment();
$segment->setIataDepartureCode('MAD');
$segment->setIataDestinationCode('BCN');
$item = new FlightItem();
$item->setName('Item 1')
      ->setCategory('physical')
      ->setReference('4912345678904')
      ->setUnitPriceWithTax(300.00)
      ->setDepartureDate('2024-01-01T00:00:00+01:00')
      ->addPassenger($passenger)
      ->addSegment($segment)
      ->setUnits(1)
      ->setTotalPriceWithTax(300.00)
      ->setAutoShipping(true);     
$transaction->addItem($item);
$parameters->setPaysolExtendedData($transaction);
```

### Step 4: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 5: Sending The Charge Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixJsChargeRequest();
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
```

This function returns multiple exceptions based on specific errors:

- **FieldException**: A parent exception for field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 6: Getting The NemuruAuthToken & NemuruCartHash

```php
echo 'nemuruAuthToken: ' . $request->getNemuruAuthToken();
echo 'nemuruCartHash: ' . $request->getNemuruCartHash();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Request 04: Charge Quix Items Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests.

The important credentials that need to be set are:

- `setMerchantId`
  - Identifier of your business on the Addon Payments platform.
- `setProductId`
  - Product identifier created for your business by AddonPayments for which the transaction must be processed.
- `setEnvironment`
  - Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
              ->setProductId(PRODUCT_ID_ITEMS)
              ->setEnvironment(Environment::STAGING);
```

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Prepay Token
- Customer Id
- Customer Email
- Merchant Transaction Id
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL
- First Name
- Last Name
- Product Id
- Date Of Birth

```php
$parameters = new Parameters();
$parameters->setAmount(300.00)
      ->setCustomerId('4')
      ->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42')
      ->setCustomerEmail('email@micomercio.com')
      ->setMerchantTransactionId('1321')
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/status')
      ->setErrorURL('https://test.com/status')
      ->setCancelURL('https://test.com/status')
      ->setAwaitingURL('https://test.com/awaiting')
      ->setFirstName('Nombre')
      ->setLastName('Apellido')
      ->setProductId('1234567890')
      ->setDob('01-12-1999');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting The Quix Parameter

In this step, we will add the parameters specifically needed for the Quix Items:

- Product Type
- Cart Currency
- Cart Total Price With Tax
- Disable Form Edition: An optional parameter to disable editing the already sent data in the request for the customer
- Billing
  - Billing First Name
  - Billing Last Name
  - Billing Address
- Items
  - Name
  - Category
  - Reference
  - Unit Price With Tax
  - Units
  - Total Price With Taxes
  - Auto Shipping

```php
$billing = new Billing();
$billing->setBillingFirstName('Nombre')
      ->setBillingLastName('Apellido Om')
      ->setBillingAddress('Nombre de la', '28003', 'Barcelona', 'ESP');
$transaction = new ItemTransaction();
$transaction->setProduct('instalments')
      ->setBilling($billing)
      ->setCartCurrency(CurrencyCodes::EUR)
      ->setDisableFormEdition(true);
$item = new ProductItem();
$item->setName('Item 1')
      ->setCategory('physical')
      ->setReference('4912345678904')
      ->setUnitPriceWithTax(300.00)
      ->setUnits(1)
      ->setTotalPriceWithTax(300.00)
      ->setAutoShipping(true);
$transaction->addItem($item);
$parameters->setPaysolExtendedData($transaction);
```

### Step 4: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 5: Sending The Charge Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixJsChargeRequest();
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
} catch (Exception $e)

 {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

This function returns multiple exceptions based on specific errors:

- **FieldException**: A parent exception for field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 6: Getting The NemuruAuthToken & NemuruCartHash

```php
echo 'nemuruAuthToken: ' . $request->getNemuruAuthToken();
echo 'nemuruCartHash: ' . $request->getNemuruCartHash();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Request 05: Charge Quix Service

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests.

The important credentials that need to be set are:

- `setMerchantId`
  - Identifier of your business on the Addon Payments platform.
- `setProductId`
  - Product identifier created for your business by AddonPayments for which the transaction must be processed.
- `setEnvironment`
  - Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
     ->setProductId(PRODUCT_ID_SERVICE)
     ->setEnvironment(Environment::STAGING);
```

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Prepay Token
- Customer Id
- Customer Email
- Merchant Transaction Id
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL
- First Name
- Last Name
- Product Id
- Date Of Birth

```php
$parameters = new Parameters();
$parameters->setAmount(300.00)
      ->setCustomerId('4')
      ->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42')
      ->setCustomerEmail('email@micomercio.com')
      ->setMerchantTransactionId('1321')
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/status')
      ->setErrorURL('https://test.com/status')
      ->setCancelURL('https://test.com/status')
      ->setAwaitingURL('https://test.com/awaiting')        
      ->setFirstName('Nombre')
      ->setLastName('Apellido')
      ->setProductId('1234567890')
      ->setDob('01-12-1999');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting The Quix Parameter

In this step, we will add the parameters specifically needed for the Quix Service:

- Product Type
- Cart Currency
- Cart Total Price With Tax
- Disable Form Edition: An optional parameter to disable editing the already sent data in the request for the customer
- Billing
  - Billing First Name
  - Billing Last Name
  - Billing Address
- Items
  - Name
  - Category
  - Reference
  - Unit Price With Tax
  - Start Date
  - End Date
  - Units
  - Total Price With Taxes
  - Auto Shipping

```php
$billing = new Billing();
$billing->setBillingFirstName('Nombre')
      ->setBillingLastName('Apellido Om')
      ->setBillingAddress('Nombre de la', '08003', 'Barcelona', 'ESP');
$transaction = new ServiceTransaction();
$transaction->setProduct('instalments')
      ->setBilling($billing)
      ->setCartCurrency(CurrencyCodes::EUR)
      ->setCartTotalPriceWithTax(300.00)
      ->setDisableFormEdition(true);
$item = new ServiceItem();
$item->setName('Item 1')
      ->setCategory('digital')
      ->setReference('4912345678904')
      ->setUnitPriceWithTax(300.00)
      ->setStartDate('2024-10-30T00:00:00+01:00')
      ->setEndDate('2024-12-31T23:59:59+01:00')
      ->setUnits(1)
      ->setTotalPriceWithTax(300.00)
      ->setAutoShipping(true);
$transaction->addItem($item);
$parameters->setPaysolExtendedData($transaction);
```

### Step 4: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 5: Sending The Charge Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixJsChargeRequest();
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
```

This function returns multiple exceptions based on specific errors:

- **FieldException**: A parent exception for field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 6: Getting The NemuruAuthToken & NemuruCartHash

```php
echo 'nemuruAuthToken: ' . $request->getResponse()->getNemuruAuthToken();
echo 'nemuruCartHash: ' . $request->getResponse()->getNemuruCartHash();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## How to Use JS Provided Examples

The example provided in the `SDK/examples/Quix/JS` directory is a ready-to-use integration for handling payments through our SDK.

Examples demonstrate how to integrate Quix services using JavaScript and server-side processing. Each service type (`Accommodation`, `Flights`, `Items`, `Service`) contains:

- `auth.php` - Authentication with the Quix service.
- `charge.php` - Processing a charge for the service.
- `index.html` - Client-side example for the specific Quix service.

To get started, follow these simple steps:

1. **Copy the Example Directory:** Copy the entire `Items` directory from `sdk/examples/Quix/JS/quixItems` into your project. This directory contains all necessary files, including `index.html`, `auth.php`, and `charge.php`, structured to work together seamlessly.

2. **Review the File Structure:** Ensure you understand the file structure and the role of each file:
   - **index.html:** Initiates the payment process in the browser.
   - **auth.php:** Handles authentication with the payment gateway.
   - **charge.php:** Processes the charge and returns `NemuruAuthToken` and `NemuruCartHash`.

3. **Configuration:** Before running the example, you may need to modify `auth.php` and `charge.php` to include your specific merchant ID, password, and other credentials relevant to your merchant and update the autoloading file path.

For more information, refer to the *Using SDK Examples* section in the

 Quick Start guide.