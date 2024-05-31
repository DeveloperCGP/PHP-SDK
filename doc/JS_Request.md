
# JavaScript SDK Documentation

## Table of Contents
- [Introduction](#introduction)
- [Namespace Import](#namespace-import)
- [Use Case 1: JavaScript Authentication Request](#use-case-1-javascript-authentication-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object)
  - [Step 2: Setting Payment Parameters](#step-2-setting-payment-parameters)
  - [Step 3: Setting Credentials And The Payment Parameters In The SDK](#step-3-setting-credentials-and-the-payment-parameters-in-the-sdk)
  - [Step 4: Sending the Authentication Request](#step-4-sending-the-authentication-request)
  - [Step 5: Retrieving the Authentication Token](#step-5-retrieving-the-authentication-token)
- [Use Case 2: JavaScript Charge Request](#use-case-2-javascript-charge-request)
  - [Step 1: Creating Credentials Object](#step-1-creating-credentials-object-1)
  - [Step 2: Setting Payment Parameters for Charge Request](#step-2-setting-payment-parameters-for-charge-request)
  - [Step 3: Setting Credentials And The Payment Parameters In The SDK](#step-3-setting-credentials-and-the-payment-parameters-in-the-sdk-1)
  - [Step 4: Sending the Charge Request](#step-4-sending-the-charge-request)
  - [Step 5: Retrieving the Redirect URL](#step-5-retrieving-the-redirect-url)
- [How to Use JS Provided Example](#how-to-use-js-provided-example)

## Introduction

This documentation focuses on how to make JavaScript transactions using the SDK. It provides step-by-step instructions to integrate the SDK and handle authentication and charge requests efficiently.

## Namespace Import

Before you can utilize the SDK for JavaScript transactions, it's crucial to import the necessary namespaces. This step ensures that your code has access to all the required classes and methods provided by the SDK. Here's how you can import these namespaces in your project:

```php
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\Types;
use AddonPaymentsSDK\Config\Enums\RecurringTypes;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
```

## Use Case 1: JavaScript Authentication Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests. The important credentials that need to be set are:

- **setMerchantId**: Identifier of your business on the Addon Payments platform.
- **setMerchantKey**: It is the JavaScript password used to verify that the request is legitimate. For the staging environment, it is sent in the welcome email; in production environments, it is retrieved through the BackOffice.
- **setProductId**: Product identifier created for your business by AddonPayments for which the transaction must be processed.
- **setEnvironment**: Switch between staging or production environment.

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
$parameters->setCurrency('EUR')
          ->setCountry('ES')
          ->setCustomerId('44')
          ->setAnonymousCustomer('true')
          ->setOperationType(OperationTypes::DEBIT);
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
       $request = $sdk->sendJsAuthRequest();
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
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Retrieving the Authentication Token

After successfully sending the authentication request, retrieve the authentication token from the response.

```php
echo $request->getAuthToken();
```

## Use Case 2: JavaScript Charge Request

### Step 1: Creating Credentials Object

First, we need to set up the credentials in the SDK to be able to send and receive requests. The important credentials that need to be set are:

- **setMerchantId**: Identifier of your business on the Addon Payments platform.
- **setProductId**: Product identifier created for your business by AddonPayments for which the transaction must be processed.
- **setEnvironment**: Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
     ->setProductId(PRODUCT_ID)
     ->setEnvironment(Environment::STAGING);
```

### Step 2: Setting Payment Parameters for Charge Request

In this step, we will provide the SDK with the payment parameters:
- Amount
- Currency
- Prepay Token
- Country
- Customer Id
- Merchant Transaction Id
- Payment Solution
- Operation Type
- Status URL
- Success URL
- Error URL
- Cancel URL
- Awaiting URL

```php
$parameters = new Parameters();
$parameters->setAmount(30)
          ->setMerchantTransactionId('1496918')
          ->setPrepayToken('97fe3726-adb1-4e24-9fb8-92593a75ae74')
          ->setCurrency('EUR')
          ->setCountry('ES')
          ->setCustomerId('44')
          ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
          ->setOperationType(OperationTypes::DEBIT)
          ->setStatusURL('https://test.com/status')
          ->setSuccessURL('https://test.com/success')
          ->setErrorURL('https://test.com/error')
          ->setCancelURL('https://test.com/cancel')
          ->setAwaitingURL('https://test.com/awaiting');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

The next step is to set the credentials and the payment parameters that we created in the SDK. To create the payment configurations, use the class `Configuration` and provide it with the payment credentials and parameters:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);       
```

### Step 4: Sending the Charge Request

```php
try {
       $request = $sdk->sendJsChargeRequest();
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
- **FieldException**: A parent exception for

 field-related errors.
  - **MissingFieldException**: Raised when mandatory data is missing, ensuring all necessary information is provided before proceeding with any operations, particularly those critical to the payment process.
  - **InvalidFieldException**: Raised when provided data is outside the expected values or is inappropriate for the given context.
- **NetworkException**: In case of network error like timeout.
- **ServerErrorException**: In case of server error like 500.
- **ClientErrorsException**: In case of client error like 400.
- **Exception**: In case of a general error occurred.

### Step 5: Retrieving the Redirect URL

Once the charge request is successfully sent, obtain the redirect URL from the response, which is used to redirect the customer to the payment gateway.

```php
$request->getResponse()->getRedirectUrl();
```

The redirect URL is where the user will complete the payment process.

**Note:** It's important to note that the status of the transaction, whether it's a success or an error, will be communicated asynchronously via a webhook notification. Within the SDK, we've included a method to create a webhook and notification handler, enabling you to receive these transaction notifications efficiently and take action. This allows for real-time updates on transaction statuses directly within your application. You can find it in the Quick Start guide.

## How to Use JS Provided Example

The example provided in the `SDK/examples/Creditcards/JS` directory is a ready-to-use integration for handling payments through our SDK. To get started, follow these simple steps:

1. **Copy the Example Directory**: Copy the entire `JS` directory from `sdk/examples/Creditcards/JS` into your project. This directory contains all necessary files, including the `index.html`, `auth.php`, and `charge.php`, structured to work together seamlessly.

2. **Review the File Structure**: Ensure you understand the file structure and the role of each file:
    - **index.html**: Initiates the payment process in the browser.
    - **auth.php**: Handles authentication with the payment gateway.
    - **charge.php**: Processes the charge and returns the redirect URL.

3. **Configuration**: Before running the example, you may need to modify `auth.php` and `charge.php` to include your specific merchant ID, password, and other credentials relevant to your merchant and update the autoloading file path.

For more information, refer to the [Using SDK Examples](https://redmine.dev.mindfulpayments.com/projects/php-payment-sdk/wiki/Introduction_and_Quick_Start#Using-SDK-Examples) documentation.