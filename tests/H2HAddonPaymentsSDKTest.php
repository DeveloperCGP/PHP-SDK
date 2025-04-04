<?php
use AddonPaymentsSDK\Config\Enums\OperationTypes;
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;

use AddonPaymentsSDK\Requests\CreateH2HRequest;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class H2HAddonPaymentsSDKTest extends TestCase
{

    public function testReciveRedirectionPaymentLink()
    {


        $reponseMock = file_get_contents(__DIR__ . '/response/h2h.xml');
        $fixedIv = 'AQEBAQEBAQEBAQEBAQEBAQ==';

        $cred = new Credentials();
        $cred->setMerchantId(1111111)
            ->setMerchantPassword(11111111111111111)
            ->setProductId(111166625)
            ->setEnvironment(Environment::STAGING);

        $parameters = new Parameters();
        $parameters->setAmount(9)
            ->setCurrency(CurrencyCodes::EUR)
            ->setCountry(CountryCodes::ES)
            ->setCustomerId('23')
            ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
            ->setOperationType(OperationTypes::DEBIT)
            ->setMerchantTransactionId('4545455')
            ->setCardNumber('4907270002222227')
            ->setExpDate('0625')
            ->setCvnNumber(123)
            ->setChName('Pablo ee')
            ->setStatusURL('https://test.com/status')
            ->setSuccessURL('https://test.com/success')
            ->setErrorURL('https://test.com/error')
            ->setCancelURL('https://test.com/cancel')
            ->setAwaitingURL('https://test.com/awaiting');


        $config = new Configuration($cred, $parameters);
        $ivGeneratorMock = function () use ($fixedIv) {
            return $fixedIv;
        };
        $createH2HRequestMock = $this->getMockBuilder(CreateH2HRequest::class)
            ->setConstructorArgs([$ivGeneratorMock])
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createH2HRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('createH2HRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createH2HRequestMock); // Inject the mock

        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendH2HPaymentRequest();
        $formatedRequest = $sendRequest->getFormattedReq();
        $encryptedRequest = $sendRequest->getEncryptedRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $paymentLink = $sendRequest->getResponse()->getRedirectUrl();
        
        $excpectedEncryptedRequest = 'Tww+J3iMPaUnc8ywOO93NxjJkCKqpGZXVdLqQA6YSkcRCKX7JmuZTtcLHncgjXrGmr3GoKewdCmQNSQrpyRrnxX5VHdg3xZIufLEe0PKlia3oGsCUMVAIIpy4WhAf8fuE9F78kLiSVa/WG5snIuVH58wbe4Hyc+rrLG62pxbRUlREJRY5LCIxPj9SiKFiVCapsxkUYpBE4ue2Ki11TfsQsnUHgKlxskRDcSFH9JpAnVslyFvz0Z5z9xVuG/lXb6OZiGEeCYCAfDr7NV9NK5tzpYG4AfDTh2zcp8LbCVPwS1eRutRaqBH/3akMoZG9slmTcIv38micqZg+wvDOiOI167SHtqZv5VH9QCeehUTq45v+Ag5Q4IiVoiNjso9OlV3k7swGpqkMk6DcY5HVwiIw9gAFpC/h6TGEGKssqso6fojfjsKQw9PjV+2y2qyiu13JImbxFs9BWqfsADChhQD2coloEomPX0I66r/Wed6qAQuEkbQhB3L5XwihzktbHRA1tDo5D+Mhh8DFLgefF1D1AnlOyps6AMoh/krlG2ShDXPXra3yfQSGdBjAy7xdezfjHmiZn3lGabGkrx0bBwr5lrulLaonzvIu4hNlXH8W5muZtwixuOIfdaQtqVTAl4ng2P6OqqkYK+xFGCy13r0mYIpVdxgHRPRcPpqvcjUR74=';
        $expectedFormatedRequest = 'amount=9&currency=EUR&country=ES&customerId=23&paymentSolution=creditcards&operationType=debit&merchantTransactionId=4545455&cardNumber=4907270002222227&expDate=0625&cvnNumber=123&chName=Pablo+ee&statusURL=https%3A%2F%2Ftest.com%2Fstatus&successURL=https%3A%2F%2Ftest.com%2Fsuccess&errorURL=https%3A%2F%2Ftest.com%2Ferror&cancelURL=https%3A%2F%2Ftest.com%2Fcancel&awaitingURL=https%3A%2F%2Ftest.com%2Fawaiting&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.0.2%3Btype%3AH2H&merchantId=1111111';

        $this->assertEquals($excpectedEncryptedRequest, $encryptedRequest, 'The formatted request does not match the expected values.');
        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');

        $this->assertContains('sdk:php;version:1.0.2;type:H2H', $merchantParams, 'Assert merchantParams in request');

        $urlPattern = '/https:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(\/\S*)?/';
        $this->assertMatchesRegularExpression($urlPattern, $paymentLink, 'Test for verifying URL presence in sendRedirectionPaymentRequest');
    }

    /**
     * @dataProvider missingParameterProvider
     */
    public function testExceptionForMissingParameters($missingParameter, $expectedExceptionMessage)
    {
        $reponseMock = file_get_contents(__DIR__ . '/response/h2h.xml');
        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Set up credentials and parameters with one missing parameters

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
        if ($missingParameter !== 'paymentSolution')
            $parameters->setPaymentSolution(PaymentSolutions::CREDITCARDS);
        if ($missingParameter !== 'cvnNumber')
            $parameters->setCvnNumber(123);
        if ($missingParameter !== 'expDate')
            $parameters->setExpDate('0625');
        if ($missingParameter !== 'operationType')
            $parameters->setOperationType(OperationTypes::DEBIT);
        if ($missingParameter !== 'cardNumber')
            $parameters->setCardNumber('4907270002222227');
        if ($missingParameter !== 'chName')
            $parameters->setChName('Pablo ee');
        if ($missingParameter !== 'statusURL')
            $parameters->setStatusURL('https://test.com/status');
        if ($missingParameter !== 'successURL')
            $parameters->setSuccessURL('https://test.com/success');
        if ($missingParameter !== 'errorURL')
            $parameters->setErrorURL('https://test.com/error');
        if ($missingParameter !== 'cancelURL')
            $parameters->setCancelURL('https://test.com/cancel');   
        if ($missingParameter !== 'awaitingURL')
            $parameters->setAwaitingURL('https://test.com/status');

        $config = new Configuration($cred, $parameters);
        $createH2HRequestMock = $this->getMockBuilder(CreateH2HRequest::class)
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createH2HRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('createH2HRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createH2HRequestMock); // Inject the mock
        $addonPaymentsSDK->sendH2HPaymentRequest();
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
            'Missing Merchant Transaction ID' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'Missing Payment Solution' => ['paymentSolution', 'Mandatory parameters are missing. Please ensure you provide:  paymentSolution.'],
            'Missing Card Number' => ['cardNumber', 'Mandatory parameters are missing. Please ensure you provide:  cardNumber.'],
            'Missing Exp Date' => ['expDate', 'Mandatory parameters are missing. Please ensure you provide:  expDate.'],
            'Missing Cvn Number' => ['cvnNumber', 'Mandatory parameters are missing. Please ensure you provide:  cvnNumber.'],
            'Missing Ch Name' => ['chName', 'Mandatory parameters are missing. Please ensure you provide:  chName.'],
            'Missing Status URL' => ['statusURL', 'Mandatory parameters are missing. Please ensure you provide:  statusURL.'],
            'Missing Success URL' => ['successURL', 'Mandatory parameters are missing. Please ensure you provide:  successURL.'],
            'Missing Error URL' => ['errorURL', 'Mandatory parameters are missing. Please ensure you provide:  errorURL.'],
            'Missing Cancel URL' => ['cancelURL', 'Mandatory parameters are missing. Please ensure you provide:  cancelURL.'],
            'awaitingURL' => ['awaitingURL', 'Mandatory parameters are missing. Please ensure you provide:  awaitingURL.'],
        ];
    }
}