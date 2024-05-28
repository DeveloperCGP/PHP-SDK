<?php

use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Requests\CreateRedirectionRequest;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

include __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;

class HostedAddonPaymentsSDKTest extends TestCase
{
   


    public function testReciveRedirectionPaymentLink()
    {


        $reponseMock = 'https://checkout.stg-eu-west1.epgint.com/EPGCheckout/rest/online/detokenize?token=70593738-9e7e-4cf9-8910-5a8848a4119a&apiVersion=5';
        $fixedIv = 'AQEBAQEBAQEBAQEBAQEBAQ==';

        $cred = new Credentials();
        $cred->setMerchantId(1111111)
            ->setMerchantPassword(11111111111111111)
            ->setProductId(111166625)
            ->setEnvironment(Environment::STAGING);

        $parameters = new Parameters();
        $parameters->setAmount(30)
            ->setCurrency(CurrencyCodes::EUR)
            ->setCountry(CountryCodes::ES)
            ->setCustomerId('13')
            ->setOperationType(OperationTypes::DEBIT)
            ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
            ->setMerchantTransactionId('87145')
            ->setStatusURL('https://test.com/status')
            ->setSuccessURL('https://test.com/success')
            ->setErrorURL('https://test.com/error')
            ->setAwaitingURL('https://test.com/awaiting');



        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        $ivGeneratorMock = function () use ($fixedIv) {
            return $fixedIv;
        };

        // Step 2: Create a mock object for CreateRedirectionRequest
        $createRedirectionRequestMock = $this->getMockBuilder(CreateRedirectionRequest::class)
        ->setConstructorArgs([$ivGeneratorMock])
        ->onlyMethods(['requestCurl']) 
        ->getMock();
        $createRedirectionRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);

        // Step 3: Use reflection to replace the createRequest property
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
      
        $property = $reflectionClass->getProperty('createRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createRedirectionRequestMock); // Inject the mock

       

        // Step 4: Proceed with your test as normal
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendRedirectionPaymentRequest();
        $formatedRequest = $sendRequest->getFormattedReq();
        $encryptedRequest = $sendRequest->getEncryptedRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $paymentLink = $sendRequest->getResponse()->getRedirectUrl();


        $expectedEncryptedRequest = 'rB33CYAw5eq1y3pVFK4DEogiwRLa0Zdxlp7fCunh8bsX2v37XgYPs09aIcTMHfMS/II31GvPmbOrxToCxZY37/l3fBpbNyQT0ZGW8U/ZoVoGMcx/YIhd+ht8sQqGDOzqkYcdIvdtev8qiaJW6hVPCNibbxpwG5AbGcSiN/eWhOD1wFckXaoJU7L8+nXBfBH36C3QyraHr8b3xjC5GhP9K1iNRyaGsTAkr+QF0jHUsothwmefaT46Z1dTIhPjr2AgasHGvT0j5GPk9zJfRu3j2gkr37zGi2Sglj4D/7BUtkN0JCCrEmODil197mjA+jNv8BMa5gDhAfjHdbSarcTe3lEISNwXNJgZ0ISe5Ievt1K0kjOhCsLBol5JSLrMgoN4tNWipmANrf2Z6K7/tUnZ3A/nlXmnYlzXV9K+7j8QK7Lds5Pk9ISBOEVeiCTpfN/5LG/A8SXjjcLALqrsp3ccdmzWU/6hHVMbzyAsr4N3Uf5JYxkKH+KQB3sxF3UaqXjtfcw95GK8T3MQWAYHPNdGbw==';

        $this->assertEquals($expectedEncryptedRequest, $encryptedRequest, 'The encrypted request does not match the expected values.');


        $expectedFormatedRequest = 'amount=30&currency=EUR&country=ES&customerId=13&operationType=debit&paymentSolution=creditcards&merchantTransactionId=87145&statusURL=https%3A%2F%2Ftest.com%2Fstatus&successURL=https%3A%2F%2Ftest.com%2Fsuccess&errorURL=https%3A%2F%2Ftest.com%2Ferror&awaitingURL=https%3A%2F%2Ftest.com%2Fawaiting&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.00%3Btype%3AHosted&merchantId=1111111';

        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');
        $this->assertContains('sdk:php;version:1.00;type:Hosted', $merchantParams, 'Assert merchantParams in request');
        // Your existing assertions...
        $urlPattern = '/https:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(\/\S*)?/';
        $this->assertMatchesRegularExpression($urlPattern, $paymentLink, 'Test for verifying URL presence in sendRedirectionPaymentRequest');
    }

    /**
     * @dataProvider missingParameterProvider
     */
    public function testExceptionForMissingParameters($missingParameter, $expectedExceptionMessage)
    {
        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Set up credentials and parameters with one missing parameter

        $cred = new Credentials();
        if ($missingParameter !== 'merchantId')
            $cred->setMerchantId(getenv('MERCHANT_ID'));
        if ($missingParameter !== 'merchantPassword')
            $cred->setMerchantPassword(getenv('MERCHANT_PASS'));
        if ($missingParameter !== 'productId')
            $cred->setProductId(getenv('PRODUCT_ID'));
        if ($missingParameter !== 'environment')
            $cred->setEnvironment(Environment::STAGING);

        $parameters = new Parameters();
        if ($missingParameter !== 'amount')
            $parameters->setAmount(30);

        if ($missingParameter !== 'currency')
            $parameters->setCurrency(CurrencyCodes::EUR);
        if ($missingParameter !== 'country')
            $parameters->setCountry(CountryCodes::ES);
        if ($missingParameter !== 'customerId')
            $parameters->setCustomerId('13');
        if ($missingParameter !== 'merchantTransactionId')
            $parameters->setMerchantTransactionId('87145');
        if ($missingParameter !== 'operationType')
            $parameters->setOperationType(OperationTypes::DEBIT);
        if ($missingParameter !== 'paymentSolution')
            $parameters->setPaymentSolution(PaymentSolutions::CREDITCARDS);
        if ($missingParameter !== 'statusURL')
            $parameters->setStatusURL('https://test.com/status');
        if ($missingParameter !== 'successURL')
            $parameters->setSuccessURL('https://test.com/success');
        if ($missingParameter !== 'errorURL')
            $parameters->setErrorURL('https://test.com/error');
        if ($missingParameter !== 'awaitingURL')
            $parameters->setAwaitingURL('https://test.com/status');

        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        // Attempt to send a redirection payment request
        $addonPaymentsSDK->sendRedirectionPaymentRequest();
    }

    static function missingParameterProvider()
    {
        return [
            'Missing Merchant Id' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Merchant Password' => ['merchantPassword', 'Mandatory credentials are missing. Please ensure you provide:  merchantPassword.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            'Missing Currency' => ['currency', 'Mandatory parameters are missing. Please ensure you provide:  currency.'],
            'Missing Amount' => ['amount', 'Mandatory parameters are missing. Please ensure you provide:  amount.'],
            'Missing Country' => ['country', 'Mandatory parameters are missing. Please ensure you provide:  country.'],
            'Missing Customer ID' => ['customerId', 'Mandatory parameters are missing. Please ensure you provide:  customerId.'],
            'Missing Operation Type' => ['operationType', 'Mandatory parameters are missing. Please ensure you provide:  operationType.'],
            'Missing Merchant Transaction ID' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'Missing Payment Solution' => ['paymentSolution', 'Mandatory parameters are missing. Please ensure you provide:  paymentSolution.'],
            'Missing Status URL' => ['statusURL', 'Mandatory parameters are missing. Please ensure you provide:  statusURL.'],
            'Missing Success URL' => ['successURL', 'Mandatory parameters are missing. Please ensure you provide:  successURL.'],
            'Missing Error URL' => ['errorURL', 'Mandatory parameters are missing. Please ensure you provide:  errorURL.'],
            'awaitingURL' => ['awaitingURL', 'Mandatory parameters are missing. Please ensure you provide:  awaitingURL.'],
        ];
    }
}
