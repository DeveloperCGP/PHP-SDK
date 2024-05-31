
# H2H Steps Documentation

This documentation focuses on how to make H2H transactions using the SDK. This payment method collects the payment data of the user and sends it to the Payment Backend.

## Table of Contents
- [Namespace Import](#namespace-import)
- [Common Prerequisite: Creating Credentials Object](#common-prerequisite-creating-credentials-object)
- [H2H Request](#h2h-request)
- [Pre-Authorization Request](#pre-authorization-request)
- [Capture Pre-Authorization](#capture-pre-authorization)
- [Void](#void)
- [Recurrent Initial](#recurrent-initial)
- [Recurrent Subsequent](#recurrent-subsequent)
- [Refund](#refund)

## Namespace Import

Before you can utilize the SDK for H2H transactions, it's crucial to import the necessary namespaces. This step ensures that your code has access to all the required classes and methods provided by the SDK. Here's how you can import these namespaces in your project:

```php
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\CardTypes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Config\Enums\Types;
use AddonPaymentsSDK\Config\Enums\RecurringTypes;
use AddonPaymentsSDK\Config\Enums\MerchantExemptionsSca;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ClientErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\NetworkException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\ServerErrorException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
```

## Common Prerequisite: Creating Credentials Object

First, set up the credentials in the SDK to send and receive requests.

The important credentials that need to be set are:
- `setMerchantId`: Identifier of your business on the Addon Payments platform.
- `setMerchantPassword`: Secret Passphrase used inside AES-256 encryption provided by AddonPayments.
- `setProductId`: Product identifier created for your business by AddonPayments.
- `setEnvironment`: Switch between staging or production environment.

```php
$cred = new Credentials();
$cred->setMerchantId(MERCHANT_ID)
     ->setMerchantPassword(MERCHANT_PASS)
     ->setProductId(PRODUCT_ID)
     ->setEnvironment(Environment::STAGING);
```

## H2H Request

### Step 1: Refer to Common Prerequisite

Before proceeding with the H2H Request, refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setAmount(9)
           ->setCurrency(CurrencyCodes::EUR)
           ->setCountry(CountryCodes::ES)
           ->setCustomerId(44)
           ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setCardNumber('4907270002222227')
           ->setExpDate('0625')
           ->setCvnNumber(123)
           ->setChName('Pablo Navarro')
           ->setOperationType(OperationTypes::DEBIT)
           ->setStatusURL('https://test.com/status')
           ->setSuccessURL('https://test.com/success')
           ->setErrorURL('https://test.com/error')
           ->setCancelURL('https://test.com/cancel')
           ->setAwaitingURL('https://test.com/awaiting');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendH2HPaymentRequest();
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

### Step 5: Get Redirection URL

Retrieve the redirection URL:

```php
$request->getResponse()->getRedirectUrl();
```

### Step 6 (Optional): Get Full Response

Obtain the full response if needed:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Pre-Authorization Request

### Step 1: Refer to Common Prerequisite

Refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setAmount(9)
           ->setCurrency(CurrencyCodes::EUR)
           ->setCountry(CountryCodes::ES)
           ->setCustomerId(44)
           ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setCardNumber('4907270002222227')
           ->setExpDate('0625')
           ->setCvnNumber(123)
           ->setChName('Pablo Navarro')
           ->setAutoCapture(false)
           ->setOperationType(OperationTypes::DEBIT)
           ->setStatusURL('https://test.com/status')
           ->setSuccessURL('https://test.com/success')
           ->setErrorURL('https://test.com/error')
           ->setCancelURL('https://test.com/cancel')
           ->setAwaitingURL('https://test.com/awaiting');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendH2HPaymentRequest();
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

### Step 5: Get Redirection URL

Retrieve the redirection URL:

```php
$request->getResponse()->getRedirectUrl();
```

### Step 6 (Optional): Get Full Response

Obtain the full response if needed:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Capture Pre-Authorization

*Note:* This is a follow-up request after successfully completing a "Pre-Authorization Request."

### Step 1: Refer to Common Prerequisite

Refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setTransactionId('1155644');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The Capture Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendCapturePaymentRequest();
} catch (InvalidFieldException $e) {
    echo 'InvalidFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (MissingFieldException $e) {
    echo 'MissingFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
    echo 'NetworkException: ' . $e->getMessage(),

 PHP_EOL;
} catch (ServerErrorException $e) {
    echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorException $e) {
    echo 'ClientErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

### Step 5: Retrieve the Response

Obtain the response:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Void

### Step 1: Refer to Common Prerequisite

Refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setTransactionId('413123123');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendVoidPaymentRequest();
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

### Step 5: Retrieve the Response

Obtain the response:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Recurrent Initial

### Step 1: Refer to Common Prerequisite

Refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setAmount(9)
           ->setCurrency(CurrencyCodes::EUR)
           ->setCountry(CountryCodes::ES)
           ->setCustomerId(44)
           ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setCardNumber('4907270002222227')
           ->setExpDate('0625')
           ->setCvnNumber(123)
           ->setChName('Pablo Navarro')
           ->setPaymentRecurringType(RecurringTypes::NEW_COF)
           ->setOperationType(OperationTypes::DEBIT)
           ->setStatusURL('https://test.com/status')
           ->setSuccessURL('https://test.com/success')
           ->setErrorURL('https://test.com/error')
           ->setCancelURL('https://test.com/cancel')
           ->setAwaitingURL('https://test.com/awaiting');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendH2HPaymentRequest();
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

### Step 5: Get Redirection URL

Retrieve the redirection URL:

```php
$request->getResponse()->getRedirectUrl();
```

### Step 6 (Optional): Retrieve Full Response

Obtain the full response if needed:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Recurrent Subsequent

### Step 1: Refer to Common Prerequisite

Refer to the *Common Prerequisite: Creating Credentials Object* section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setAmount(9)
           ->setCurrency(CurrencyCodes::EUR)
           ->setCountry(CountryCodes::ES)
           ->setCustomerId(44)
           ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setMerchantExemptionsSca(MerchantExemptionsSca::MIT)
           ->setPaymentRecurringType(RecurringTypes::COF)
           ->setCardNumberToken('6537275043632227')
           ->setChName('Appellio Sam')
           ->setSubscriptionPlan('898006493817111')
           ->setOperationType(OperationTypes::DEBIT)
           ->setStatusURL('https://test.com/status')
           ->setSuccessURL('https://test.com/success')
           ->setErrorURL('https://test.com/error')
           ->setCancelURL('https://test.com/cancel')
           ->setAwaitingURL('https://test.com/awaiting');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendH2HPaymentRequest();
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

### Step 5: Get Redirection URL

Retrieve the redirection URL:

```php
$request->getResponse()->getRedirectUrl();
```

### Step 6 (Optional): Retrieve Full Response

Obtain the full response if needed:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.

## Refund

### Step 1: Refer to Common Prerequisite

Refer to the Common Prerequisite: Creating Credentials Object section.

### Step 2: Creating Payment Parameter Object

Provide the SDK with the payment parameters:

```php
$parameters = new Parameters();
$parameters->setAmount(9)
           ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
           ->setMerchantTransactionId('4545455')
           ->setTransactionId('413123123');
```

### Step 3: Setting Credentials And The Payment Parameters In The SDK

Set the credentials and the payment parameters in the SDK:

```php
$config = new Configuration($cred, $parameters, $env);
$sdk = new AddonPaymentsSDK($config);
```

### Step 4: Send The H2H Request

Create the request object to send and receive from the backend:

```php
try {
    $request = $sdk->sendRefundPaymentRequest();
} catch (InvalidFieldException $e) {
    echo 'InvalidFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (MissingFieldException $e) {
    echo 'MissingFieldException: ' . $e->getMessage(), PHP_EOL;
} catch (NetworkException $e) {
    echo 'NetworkException: ' . $e->getMessage(), PHP_EOL;
} catch (ServerErrorException $e) {
    echo 'ServerErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (ClientErrorException $e) {
    echo

 'ClientErrorException: ' . $e->getMessage(), PHP_EOL;
} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage(), PHP_EOL;
}
```

### Step 5: Retrieve the Response

Obtain the response:

```php
$request->getResponse()->getRawResponse();
```

*Note:* The status of the transaction will be communicated asynchronously via a webhook notification.