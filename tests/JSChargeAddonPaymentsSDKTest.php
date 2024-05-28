<?php
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Requests\CreateChargeRequest;
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
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class JSChargeAddonPaymentsSDKTest extends TestCase
{

    public function testReciveRedirectionUrl()
    {


        $responseMock = file_get_contents(__DIR__ . '/response/charge.json');

        
        $cred = new Credentials();
        $cred->setMerchantId(getenv('MERCHANT_ID'))
            ->setMerchantKey(getenv('MERCHANT_KEY'))
            ->setProductId(getenv('PRODUCT_ID'))
            ->setEnvironment(Environment::STAGING);
        $parameters = new Parameters();
        $parameters->setAmount(30)
            ->setMerchantTransactionId('1496918')
            ->setPrepayToken('97fe3726-adb1-4e24-9fb8-92593a75ae74')
            ->setAmount(10)
            ->setCurrency(CurrencyCodes::EUR)
            ->setOperationType(OperationTypes::DEBIT)
            ->setApiVersion(5)
            ->setCountry(CountryCodes::ES)
            ->setCustomerId('44')
            ->setPaymentSolution(PaymentSolutions::CREDITCARDS)
            ->setStatusURL('https://test.com/status')
            ->setSuccessURL('https://test.com/success')
            ->setErrorURL('https://test.com/error')
            ->setAwaitingURL('https://test.com/awaiting');


        $config = new Configuration($cred, $parameters);
        $createChargeRequestMock = $this->getMockBuilder(CreateChargeRequest::class)
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
            $createChargeRequestMock->method('requestCurl')->willReturn(['response' => $responseMock, 'status_code' => 200, 'message' => null]);

        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('jsChargeRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createChargeRequestMock); // Inject the mock
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendJsChargeRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $paymentLink = $sendRequest->getResponse()->getRedirectUrl();
        
        $this->assertContains('sdk:php;version:1.00;type:JsCharge', $merchantParams, 'Assert merchantParams in request');

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
        if ($missingParameter !== 'productId')
            $cred->setProductId(getenv('PRODUCT_ID'));
        if ($missingParameter !== 'environment')    
            $cred->setEnvironment(Environment::STAGING);    

        $parameters = new Parameters();
        if ($missingParameter !== 'amount')
            $parameters->setAmount(30);
        if ($missingParameter !== 'merchantTransactionId')    
            $parameters->setMerchantTransactionId('1496918');
        if ($missingParameter !== 'prepayToken')
            $parameters->setPrepayToken('97fe3726-adb1-4e24-9fb8-92593a75ae74');
        if ($missingParameter !== 'currency')
            $parameters->setCurrency(CurrencyCodes::EUR);
        if ($missingParameter !== 'country')
            $parameters->setCountry(CountryCodes::ES);
        if( $missingParameter !== 'operationType')
            $parameters->setOperationType(OperationTypes::DEBIT);
        if($missingParameter !== 'apiVersion')
            $parameters->setApiVersion(5);    
        if ($missingParameter !== 'customerId')
            $parameters->setCustomerId('13');
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
        $addonPaymentsSDK->sendJsChargeRequest();
    }

    static function missingParameterProvider()
    {
        return [
            'Missing Merchant Id' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Merchant Transaction Id' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            'Missing Amount' => ['amount', 'Mandatory parameters are missing. Please ensure you provide:  amount.'],
            'Missing Currency' => ['currency', 'Mandatory parameters are missing. Please ensure you provide:  currency.'],
            'Missing Country' => ['country', 'Mandatory parameters are missing. Please ensure you provide:  country.'],
            'Missing Customer ID' => ['customerId', 'Mandatory parameters are missing. Please ensure you provide:  customerId.'],
            'Missing Api Version' => ['apiVersion', 'Mandatory parameters are missing. Please ensure you provide:  apiVersion.'],
            'Missing PrepayToken' => ['prepayToken', 'Mandatory parameters are missing. Please ensure you provide:  prepayToken.'],
            'Missing Status URL' => ['statusURL', 'Mandatory parameters are missing. Please ensure you provide:  statusURL.'],
            'Missing Success URL' => ['successURL', 'Mandatory parameters are missing. Please ensure you provide:  successURL.'],
            'Missing Error URL' => ['errorURL', 'Mandatory parameters are missing. Please ensure you provide:  errorURL.'],
            'Missing Error Awaiting URL' => ['awaitingURL', 'Mandatory parameters are missing. Please ensure you provide:  awaitingURL.'],

        ];
    }


}