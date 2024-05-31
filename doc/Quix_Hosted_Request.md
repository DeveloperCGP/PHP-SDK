
# Quix Hosted

## Table of Contents
- [Namespace Import](#namespace-import)
- [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object)
- [Quix Accommodation](#quix-accommodation)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk)
  - [Step 5: Sending The Hosted Request](#step-5-sending-the-hosted-request)
  - [Step 6: Getting The Redirection URL](#step-6-getting-the-redirection-url)
- [Quix Flight](#quix-flight)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite-1)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-1)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-1)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-1)
  - [Step 5: Sending The Hosted Request](#step-5-sending-the-hosted-request-1)
  - [Step 6: Getting The Redirection URL](#step-6-getting-the-redirection-url-1)
- [Quix Items](#quix-items)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite-2)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-2)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-2)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-2)
  - [Step 5: Sending The Hosted Request](#step-5-sending-the-hosted-request-2)
  - [Step 6: Getting The Redirection URL](#step-6-getting-the-redirection-url-2)
- [Quix Service](#quix-service)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite-3)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-3)
  - [Step 3: Setting The Quix Parameter](#step-3-setting-the-quix-parameter-3)
  - [Step 4: Setting Credentials And The Payment Parameters In The SDK](#step-4-setting-credentials-and-the-payment-parameters-in-the-sdk-3)
  - [Step 5: Sending The Hosted Request](#step-5-sending-the-hosted-request-3)
  - [Step 6: Getting The Redirection URL](#step-6-getting-the-redirection-url-3)

## Namespace Import

Before you can utilize the SDK for Quix Hosted transactions, it's crucial to import the necessary namespaces. This step ensures that your code has access to all the required classes and methods provided by the SDK. Here's how you can import these namespaces in your project:

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

## Common Prerequisite: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests. The important credentials that need to be set are:

- **setMerchantId**: Identifier of your business on the Addon Payments platform.
- **setMerchantPassword**: The Secret Passphrase used inside AES-256 encryption provided by AddonPayments.
- **setProductId**: Product identifier created for your business by AddonPayments for which the transaction must be processed.
- **setEnvironment**: Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
     ->setMerchantPassword(MERCHANT_PASS)
     ->setProductId(PRODUCT_ID)
     ->setEnvironment(Environment::STAGING);
```

## Quix Accommodation

*Quix Accommodation* is an innovative feature for the hospitality and lodging industry. It integrates a seamless "Buy Now, Pay Later" payment option, allowing customers to reserve accommodations without immediate payment.

### Step 1: Refer to Common Prerequisite

Before proceeding with the Hosted Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
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
  - Check-In Date
  - Check-Out Date
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
      ->set

Guests(3)
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

### Step 5: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixRedirectionPaymentRequest();
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

### Step 6: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Quix Flight

*Quix Flight* is a flexible "Buy Now, Pay Later" service within the Quix PHP SDK, tailored for the airline industry, enabling customers to book flights immediately and pay at a later date.

### Step 1: Refer to Common Prerequisite

Before proceeding with the Hosted Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
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
  - Department Date
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

### Step 5: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixRedirectionPaymentRequest();
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

### Step 6: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your

 application. You can find it in the Quick Start guide.

## Quix Items

*Quix Items* offers a "Buy Now, Pay Later" option for retail products, allowing customers to make immediate purchases and defer payments.

### Step 1: Refer to Common Prerequisite

Before proceeding with the Hosted Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
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

### Step 5: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixRedirectionPaymentRequest();
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

### Step 6: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Quix Service

*Quix Service* integrates a "Buy Now, Pay Later" payment method into various services through the Quix PHP SDK, providing customers the flexibility to access services immediately while deferring payment.

### Step 1: Refer to Common Prerequisite

Before proceeding with the Hosted Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
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

The next step is to set the credentials and the payment parameters that we created in the SDK

. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 5: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendQuixRedirectionPaymentRequest();
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

### Step 6: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.