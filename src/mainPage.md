
# AddonPayments PHP SDK

  

## Introduction

This SDK is responsible for handling different payment methods (Host2Host, Hosted and JS Library) through simple functions calls by providing a few parameter needed in the payment (amount, currency, etc...)

  
  

## Environment Requirement

  

Version >= PHP 8.0

  
  

## Autoloading the SDK

  

    include __DIR__ . '/vendor/autoload.php';

  

## Namespace Imports

    use AddonPaymentsSDK\AddonPaymentsSDK;
    use AddonPaymentsSDK\Config\Configuration;
    use AddonPaymentsSDK\Config\CountryCodes;
    use AddonPaymentsSDK\Config\Parameters;
    use AddonPaymentsSDK\Config\Credentials;
    use AddonPaymentsSDK\Config\CurrencyCodes;

## Currently Supported Operations

 -  Hosted Redirection
 -  Host to Host
  -  Javascript Auth token
  -  Javascript Charge Request
  - Quix Hosted
  - Quix Javascript
