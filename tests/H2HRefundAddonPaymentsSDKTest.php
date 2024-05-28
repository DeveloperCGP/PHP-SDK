<?php
use AddonPaymentsSDK\Requests\CreateRefundRequest;
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\PaymentSolutions;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class H2HRefundAddonPaymentsSDKTest extends TestCase
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
        $parameters->setAmount(30)
            ->setPaymentSolution(PaymentSolutions::CAIXAPUCPUCE)
            ->setMerchantTransactionId('87145')
            ->setTransactionId('7693681');

        $ivGeneratorMock = function () use ($fixedIv) {
                return $fixedIv;
        };

        $config = new Configuration($cred, $parameters);

        $createRefundRequestMock = $this->getMockBuilder(CreateRefundRequest::class)
            ->setConstructorArgs([$ivGeneratorMock])
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createRefundRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('createRefundRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createRefundRequestMock); // Inject the mock
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendRefundPaymentRequest();
        $formatedRequest = $sendRequest->getFormattedReq();
        $encryptedRequest = $sendRequest->getEncryptedRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $response = $sendRequest->getResponse()->getRawResponse();
        $excepctedEncryptedRequest = 'Ivru45o5oVWiWi1UR/u74exZ1t0Lup9pVKIzRnWRXtcG1cNvLegZ2522JbwwVIYUgAvAP0Ey7i0N02whn0GeMuwj68k6GDodTEBzAY+7bCjyecKaERyj3SPad5YjeEIXYvaZo08ztZtDtHDc9M0gV07+XHl3BsQ6XHakXCF6leJ6B/KhRFy/zffrzELmkf6kRuedoADiEpu3/3257YwMudkg9C77Cu77+dzqfl7Xh0WOSbIPIYKRUtH011ZbCEGD';
        $expectedFormatedRequest = 'amount=30&paymentSolution=caixapucpuce&merchantTransactionId=87145&transactionId=7693681&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.00%3Btype%3ARefund&merchantId=1111111';

        $this->assertEquals($excepctedEncryptedRequest, $encryptedRequest, 'The formatted request does not match the expected values.');
        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');
        $this->assertContains('sdk:php;version:1.00;type:Refund', $merchantParams, 'Assert merchantParams in request');
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
        if ($missingParameter !== 'amount')
            $parameters->setAmount(30);
        if ($missingParameter !== 'merchantTransactionId')
            $parameters->setMerchantTransactionId('87145');
        if ($missingParameter !== 'paymentSolution')
            $parameters->setPaymentSolution(PaymentSolutions::CAIXAPUCPUCE);
        if ($missingParameter !== 'transactionId')
            $parameters->setTransactionId('7693681');
        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        // Attempt to send a redirection payment request
        $addonPaymentsSDK->sendRefundPaymentRequest();
    }

    static function missingParameterProvider()
    {
        return [
            'Missing Merchant Id' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Merchant Password' => ['merchantPassword', 'Mandatory credentials are missing. Please ensure you provide:  merchantPassword.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'], 
            'Missing Amount' => ['amount', 'Mandatory parameters are missing. Please ensure you provide:  amount.'],
            'Missing Merchant Transaction ID' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'Missing Payment Solution' => ['paymentSolution', 'Mandatory parameters are missing. Please ensure you provide:  paymentSolution.'],
            'Missing Transaction ID' => ['transactionId', 'Mandatory parameters are missing. Please ensure you provide:  transactionId.'],
        ];
    }
}