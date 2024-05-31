
# Hosted Documentation

## Table of Contents
- [Introduction](#introduction)
- [Namespace Import](#namespace-import)
- [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object)
- [Hosted](#hosted)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object)
  - [Step 3: Setting Credentials And The Payment Parameters In The SDK](#step-3-setting-credentials-and-the-payment-parameters-in-the-sdk)
  - [Step 4: Sending The Hosted Request](#step-4-sending-the-hosted-request)
  - [Step 5: Getting The Redirection URL](#step-5-getting-the-redirection-url)
- [Recurrent](#recurrent)
  - [Step 1: Refer to Common Prerequisite](#step-1-refer-to-common-prerequisite-1)
  - [Step 2: Creating Payment Parameter Object](#step-2-creating-payment-parameter-object-1)
  - [Step 3: Setting Credentials And The Payment Parameters In The SDK](#step-3-setting-credentials-and-the-payment-parameters-in-the-sdk-1)
  - [Step 4: Sending The Hosted Request](#step-4-sending-the-hosted-request-1)
  - [Step 5: Getting The Redirection URL](#step-5-getting-the-redirection-url-1)

## Introduction

This documentation focuses on how to make Hosted transactions using the SDK. This payment method involves sending the payment details and then showing a web page directed from AddonPayments for the user to enter the card data and proceed with the transaction.

## Namespace Import

Before you can utilize the SDK for Hosted transactions, it's crucial to import the necessary namespaces. This step ensures that your code has access to all the required classes and methods provided by the SDK. Here's how you can import these namespaces in your project:

```php
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\Types;
use AddonPaymentsSDK\Config\Enums\RecurringTypes;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
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

## Hosted

### Step 1: Refer to Common Prerequisite

Before proceeding with the Hosted Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Currency
- Country
- Customer Id
- Payment Solution
- Merchant Transaction Id
- Operation Type
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL

```php
$parameters = new Parameters();
$parameters->setAmount(30)
      ->setCurrency(CurrencyCodes::EUR)
      ->setCountry(CountryCodes::ES)
      ->setCustomerId('13')
      ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
      ->setMerchantTransactionId('87145')
      ->setOperationType(OperationTypes::DEBIT)
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/success')
      ->setErrorURL('https://test.com/error')
      ->setCancelURL('https://test.com/cancel')
      ->setAwaitingURL('https://test.com/awaiting');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 4: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendRedirectionPaymentRequest();
} catch (InvalidArgumentException $e) {
       echo 'InvalidArgumentException' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
       echo 'NetworkException: ' . $e->getMessage(), PHP_EOL;
} catch (ServerErrorException $e) {
       echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorsException $e) {
       echo 'ClientErrorsException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

This function returns multiple exceptions based on specific errors:

- **InvalidArgumentException**: In case of an invalid parameter set.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Recurrent

### Step 1: Refer to Common Prerequisite

Before proceeding with the Recurrent Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Currency
- Country
- Customer Id
- Payment Solution
- Merchant Transaction Id
- Payment Recurrent Type
- Operation Type
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL

```php
$parameters = new Parameters();
$parameters->setAmount(30)
      ->setCurrency(CurrencyCodes::EUR)
      ->setCountry(CountryCodes::ES)
      ->setCustomerId('13')
      ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
      ->setMerchantTransactionId('87145')
      ->setPaymentRecurringType(RecurringTypes::NEW_COF)
      ->setOperationType(OperationTypes::DEBIT)
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/success')
      ->setErrorURL('https://test.com/error')
      ->setCancelURL('https://test.com/cancel')
      ->setAwaitingURL('https://test.com/awaiting');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide

it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 4: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendRedirectionPaymentRequest();
} catch (InvalidArgumentException $e) {
       echo 'InvalidArgumentException' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
       echo 'NetworkException: ' . $e->getMessage(), PHP_EOL;
} catch (ServerErrorException $e) {
       echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorsException $e) {
       echo 'ClientErrorsException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

This function returns multiple exceptions based on specific errors:

- **InvalidArgumentException**: In case of an invalid parameter set.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## Recurrent

### Step 1: Refer to Common Prerequisite

Before proceeding with the Recurrent Request, please refer to the [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object) section at the beginning of this documentation for the initial setup of the SDK credentials. Ensure you have correctly configured your credentials as described there.

### Step 2: Creating Payment Parameter Object

In this step, we will provide the SDK with the payment parameters:

- Amount
- Currency
- Country
- Customer Id
- Payment Solution
- Merchant Transaction Id
- Payment Recurrent Type
- Operation Type
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL

```php
$parameters = new Parameters();
$parameters->setAmount(30)
      ->setCurrency(CurrencyCodes::EUR)
      ->setCountry(CountryCodes::ES)
      ->setCustomerId('13')
      ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
      ->setMerchantTransactionId('87145')
      ->setPaymentRecurringType(RecurringTypes::NEW_COF)
      ->setOperationType(OperationTypes::DEBIT)
      ->setStatusURL('https://test.com/status')
      ->setSuccessURL('https://test.com/success')
      ->setErrorURL('https://test.com/error')
      ->setCancelURL('https://test.com/cancel')
      ->setAwaitingURL('https://test.com/awaiting');
```

The setter functions return an exception `InvalidArgumentException` in case of an invalid parameter sent to the function.

### Step 3: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 4: Sending The Hosted Request

Now it's time to create the request object that will be used to send and receive from the backend:

```php
try {
       // Sending the payment request to AddonPayments and receiving a redirection URL for the payment gateway.
       $request = $sdk->sendRedirectionPaymentRequest();
} catch (InvalidArgumentException $e) {
       echo 'InvalidArgumentException' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
       echo 'NetworkException: ' . $e->getMessage(), PHP_EOL;
} catch (ServerErrorException $e) {
       echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorsException $e) {
       echo 'ClientErrorsException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
       echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

This function returns multiple exceptions based on specific errors:

- **InvalidArgumentException**: In case of an invalid parameter set.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Getting The Redirection URL

The backend response will be only the redirection URL, so we can get it by calling:

```php
$request->getResponse()->getRedirectUrl();
```

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.