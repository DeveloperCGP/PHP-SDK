<?php
use AddonPaymentsSDK\Config\Enums\OperationTypes;
use AddonPaymentsSDK\Requests\CreateAuthTokenRequest;
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class JSAuthAddonPaymentsSDKTest extends TestCase
{
    
    public function testReciveAuthToken()
    {


        $responseMock = '{"authToken":"305f63bf-b666-49ba-96c8-220e7948962d"}';

        $cred = new Credentials();
        $cred->setMerchantId(getenv('MERCHANT_ID'))
            ->setMerchantKey(getenv('MERCHANT_KEY'))
            ->setProductId(getenv('PRODUCT_ID'))
            ->setEnvironment(Environment::STAGING);

            $parameters = new Parameters();
            $parameters->setCurrency(CurrencyCodes::EUR)
                ->setCountry(CountryCodes::ES)
                ->setCustomerId('44')
                ->setOperationType(OperationTypes::DEBIT)
                ->setAnonymousCustomer('true');


        $config = new Configuration($cred, $parameters);
        $createAuthRequestMock = $this->getMockBuilder(CreateAuthTokenRequest::class)
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createAuthRequestMock->method('requestCurl')->willReturn(['response' => $responseMock, 'status_code' => 200, 'message' => null]);
      
        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('jsAuthTokenRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createAuthRequestMock); // Inject the mock
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendJsAuthRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $authToken = $sendRequest->getResponse()->getAuthToken();
        
        $this->assertContains('sdk:php;version:1.00;type:JsAuth', $merchantParams, 'Assert merchantParams in request');

        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        $this->assertMatchesRegularExpression($uuidPattern, $authToken, 'Received authToken is not in the correct UUID format');

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
        if ($missingParameter !== 'merchantKey')
            $cred->setMerchantKey(getenv('MERCHANT_KEY'));
        if ($missingParameter !== 'productId')
            $cred->setProductId(getenv('PRODUCT_ID'));
        if ($missingParameter !== 'environment')    
            $cred->setEnvironment(Environment::STAGING);    

        $parameters = new Parameters();
        if ($missingParameter !== 'amount')
            $parameters->setAmount(30);
        if($missingParameter !== 'operationType')    
            $parameters->setOperationType(OperationTypes::DEBIT);
        if ($missingParameter !== 'currency')
            $parameters->setCurrency(CurrencyCodes::EUR);
        if ($missingParameter !== 'country')
            $parameters->setCountry(CountryCodes::ES);
        if ($missingParameter !== 'customerId')
            $parameters->setCustomerId('13');


        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        // Attempt to send a redirection payment request
        $addonPaymentsSDK->sendJsAuthRequest();
    }

    static function missingParameterProvider()
    {
        return [
            'Missing Merchant Id' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            'Missing Merchant Key' => ['merchantKey', 'Mandatory credentials are missing. Please ensure you provide:  merchantKey.'],
            'Missing Operation Type' => ['operationType', 'Mandatory parameters are missing. Please ensure you provide:  operationType.'],
            'Missing Currency' => ['currency', 'Mandatory parameters are missing. Please ensure you provide:  currency.'],
            'Missing Country' => ['country', 'Mandatory parameters are missing. Please ensure you provide:  country.'],
            'Missing Customer ID' => ['customerId', 'Mandatory parameters are missing. Please ensure you provide:  customerId.'],

        ];
    }
}