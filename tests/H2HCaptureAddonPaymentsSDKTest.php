<?php
use AddonPaymentsSDK\Requests\CreateCaptureRequest;
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class H2HCaptureAddonPaymentsSDKTest extends TestCase
{

    public function testReciveResponse()
    {


        $reponseMock = file_get_contents(__DIR__ . '/response/void.xml');
        $fixedIv = 'AQEBAQEBAQEBAQEBAQEBAQ==';



        $cred = new Credentials();
        $cred->setMerchantId(1111111)
            ->setMerchantPassword(11111111111111111)
            ->setProductId(111166625)
            ->setEnvironment(Environment::STAGING);

        // Setting up payment request parameters, including customer and transaction details.       
        $parameters = new Parameters();
        $parameters
            ->setPaymentSolution(PaymentSolutions::CAIXAPUCPUCE)
            ->setMerchantTransactionId('87145')
            ->setTransactionId('7693681');

        $config = new Configuration($cred, $parameters);
        $ivGeneratorMock = function () use ($fixedIv) {
            return $fixedIv;
        };
        $createCaptureRequestMock = $this->getMockBuilder(CreateCaptureRequest::class)
            ->setConstructorArgs([$ivGeneratorMock])
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createCaptureRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('createCaptureRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createCaptureRequestMock); // Inject the mock
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendCapturePaymentRequest();
        $formatedRequest = $sendRequest->getFormattedReq();
        $encryptedRequest = $sendRequest->getEncryptedRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $response = $sendRequest->getResponse()->getRawResponse();
        
        $excpectedEncryptedRequest = 'g4lqI0iz6/VzgHQjJK3Wxb0eOI+6REw11PvpTroAGp1i8Rnogzlx+XNb3mS8gmE8fo9jVLo0XIlCMgX20TvAAiTRD94j4fJUJAiEGVhrp6U3AaYyqoJ5GpO7737iPIUoeBHmEKZWL3BCdFZdLH/ZTwvUhp5Jes2rATwrdkr6+h3rrIXOw9TRFGhDi7DuxhpFE+8V5Twj8TYXNLt0NSDCZAQgOSyS2lQ1tFEsWwvhzBsmTJ3SI2pLhe5+Qbi0lUn7';
        $expectedFormatedRequest = 'paymentSolution=caixapucpuce&merchantTransactionId=87145&transactionId=7693681&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.0.2%3Btype%3ACapture&merchantId=1111111';

        $this->assertEquals($excpectedEncryptedRequest, $encryptedRequest, 'The formatted request does not match the expected values.');


        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');

        $this->assertContains('sdk:php;version:1.0.2;type:Capture', $merchantParams, 'Assert merchantParams in request');
       
        $xml = simplexml_load_string($response);
        $status = (string) $xml->operations->operation->status;

        $this->assertSame('SUCCESS', $status);
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
        if ($missingParameter !== 'merchantTransactionId')
            $parameters->setMerchantTransactionId('87145');
        if ($missingParameter !== 'paymentSolution')
            $parameters->setPaymentSolution(PaymentSolutions::CAIXAPUCPUCE);
        if ($missingParameter !== 'transactionId')
            $parameters->setTransactionId('7693681');
        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        // Attempt to send a redirection payment request
        $addonPaymentsSDK->sendCapturePaymentRequest();
    }

    static function missingParameterProvider()
    {
        return [
            'Missing Merchant Id' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Merchant Password' => ['merchantPassword', 'Mandatory credentials are missing. Please ensure you provide:  merchantPassword.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            'Missing Merchant Transaction ID' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'Missing Payment Solution' => ['paymentSolution', 'Mandatory parameters are missing. Please ensure you provide:  paymentSolution.'],
            'Missing Transaction ID' => ['transactionId', 'Mandatory parameters are missing. Please ensure you provide:  transactionId.'],
        ];
    }
}