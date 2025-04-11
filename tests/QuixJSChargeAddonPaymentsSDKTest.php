<?php
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\Category;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;
use AddonPaymentsSDK\Config\PaySolExtended\ItemTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\CreateQuixChargeRequest;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class QuixJSChargeAddonPaymentsSDKTest extends TestCase
{

    public function testReciveTokens()
    {

        $responseMock = file_get_contents(__DIR__ . '/response/quixcharge.json');
        $cred = new Credentials();
        $cred->setMerchantId(getenv('MERCHANT_ID'))
            ->setMerchantKey(getenv('MERCHANT_KEY'))
            ->setProductId(getenv('PRODUCT_ID_PRODUCTS'))
            ->setEnvironment(Environment::STAGING);
        $parameters = new QuixParameters();
        $parameters->setAmount(300.00)
            ->setCustomerId('44')
            ->setCustomerCountry(CountryCodes::ES)
            ->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42')
            ->setCustomerEmail('test@mail.com')
            ->setMerchantTransactionId('87315')
            ->setCustomerNationalId('99999999R')
            ->setIpAddress('192.168.1.1')
            ->setStatusURL('https://test.com/status')
            ->setSuccessURL('https://test.com/status')
            ->setErrorURL('https://test.com/status')
            ->setCancelURL('https://test.com/status')
            ->setAwaitingURL('https://test.com/status')
            ->setFirstName('Nombre')
            ->setDob('01-12-1999')
            ->setLastName('Apellido');

        // Construction of a billing profile and a shopping cart with products. 
        $billing = new Billing();
        $billing->setBillingFirstName('Nombre')
            ->setBillingLastName('Apellido SegundoApellido')
            ->setBillingAddress('Nombre de la', '08003', 'Barcelona', 'ESP');
        $transaction = new ItemTransaction();
        $transaction->setProduct('instalments')
            ->setBilling($billing)
            ->setCartCurrency(CurrencyCodes::EUR)
            ->setCartTotalPriceWithTax(300.00);
        $item = new ProductItem();
        $item->setName('Item 1')
            ->setCategory(Category::PHYSICAL)
            ->setReference('4912345678904')
            ->setUnitPriceWithTax(300.00)
            ->setUnits(1)
            ->setTotalPriceWithTax(300.00)
            ->setAutoShipping(true);
        $transaction->addItem($item);
        $parameters->setPaysolExtendedData($transaction);

        $config = new Configuration($cred, $parameters);
        $createChargeRequestMock = $this->getMockBuilder(CreateQuixChargeRequest::class)
            ->onlyMethods(['requestCurl']) // Mock only sendRequest method
            ->getMock();
        $createChargeRequestMock->method('requestCurl')->willReturn(['response' => $responseMock, 'status_code' => 200, 'message' => null]);

        $addonPaymentsSDK = new AddonPaymentsSDK($config);
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('jsQuixChargeRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createChargeRequestMock); // Inject the mock
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendQuixJsChargeRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $request = $sendRequest->getResponse();
        
        $this->assertContains('sdk:php;version:1.0.2;type:QuixCharge', $merchantParams, 'Assert merchantParams in request');
        $nemuruAuthToken = $request->getNemuruAuthToken();
        $nemuruCash = $request->getNemuruCartHash();
        $pattern = '/^[A-Za-z0-9]{32}$/';


        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        $this->assertMatchesRegularExpression($pattern, $nemuruAuthToken);
        $this->assertMatchesRegularExpression($uuidPattern, $nemuruCash, 'Received authToken is not in the correct UUID format');

    }
    /**
     * @dataProvider missingQuixParameterProvider
     */
    public function testExceptionForMissingQuixParameters($missingParameter, $expectedExceptionMessage)
    {
        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $cred = new Credentials();
        if ($missingParameter !== 'merchantId')
            $cred->setMerchantId(getenv('MERCHANT_ID'));
        if ($missingParameter !== 'productId')    
            $cred->setProductId(getenv('PRODUCT_ID_PRODUCTS'));
        if ($missingParameter !== 'environment')    
            $cred->setEnvironment(Environment::STAGING);


        $parameters = new QuixParameters();
        if ($missingParameter !== 'prepayToken')
            $parameters->setPrepayToken('dc4ddb5b-fd87-4d9a-9df4-c8c0fdfcec42');
        if ($missingParameter !== 'customerId')
            $parameters->setCustomerId('11');
        if ($missingParameter !== 'customerEmail')
            $parameters->setCustomerEmail('test@mail.com');
        if ($missingParameter !== 'customerNationalId')
            $parameters->setCustomerNationalId('99999999R');
        if ($missingParameter !== 'merchantTransactionId')
            $parameters->setMerchantTransactionId('87315');
        if ($missingParameter !== 'statusURL')
            $parameters->setStatusURL('https://test.com/status');
        if ($missingParameter !== 'successURL')
            $parameters->setSuccessURL('https://test.com/status');
        if ($missingParameter !== 'errorURL')
            $parameters->setErrorURL('https://test.com/status');
        if ($missingParameter !== 'cancelURL')
            $parameters->setCancelURL('https://test.com/status');
        if ($missingParameter !== 'awaitingURL')
            $parameters->setAwaitingURL('https://test.com/status');     
        if ($missingParameter !== 'firstName')
            $parameters->setFirstName('Nombre');
        if ($missingParameter !== 'dob')
            $parameters->setDob('01-12-1999');
        if ($missingParameter !== 'ipAddress')
            $parameters->setIpAddress('192.168.1.1');
        if ($missingParameter !== 'lastName')
            $parameters->setLastName('Apellido');

        $billing = new Billing();
        if ($missingParameter !== 'billingFirstName')
            $billing->setBillingFirstName('Nombre');
        if ($missingParameter !== 'billingLastName')
            $billing->setBillingLastName('Apellido SegundoApellido');
        if ($missingParameter !== 'billingAddress')
            $billing->setBillingAddress('Nombre de la', '28003', 'Barcelona', 'ESP');

        $transaction = new ItemTransaction();

        if ($missingParameter !== 'product')
            $transaction->setProduct('instalments');
        if ($missingParameter !== 'billing')
            $transaction->setBilling($billing);
        if ($missingParameter !== 'cartCurrency')
            $transaction->setCartCurrency(CurrencyCodes::EUR);

        $item = new ProductItem();
        if ($missingParameter !== 'itemName')
            $item->setName('Item 1');
        if ($missingParameter !== 'category')
            $item->setCategory(Category::PHYSICAL);
        if ($missingParameter !== 'reference')
            $item->setReference('4912345678904');
        if ($missingParameter !== 'unitPriceWithTax')
            $item->setUnitPriceWithTax(300.00);
        if ($missingParameter !== 'units')
            $item->setUnits(1);
        if ($missingParameter !== 'totalPriceWithTax')
            $item->setTotalPriceWithTax(300.00);


        $transaction->addItem($item);

        if ($missingParameter !== 'paysolExtendedData')
            $parameters->setPaysolExtendedData($transaction);

        $config = new Configuration($cred, $parameters);
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        $addonPaymentsSDK->sendQuixJsChargeRequest(); 
    }

    public static function missingQuixParameterProvider()
    {
        return [
            'prepayToken' => ['prepayToken', 'Mandatory parameters are missing. Please ensure you provide:  prepayToken.'],
            'merchantId' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            'customerId' => ['customerId', 'Mandatory parameters are missing. Please ensure you provide:  customerId.'],
            'customerEmail' => ['customerEmail', 'Mandatory parameters are missing. Please ensure you provide:  customerEmail.'],
            'customerNationalId' => ['customerNationalId', 'Mandatory parameters are missing. Please ensure you provide:  customerNationalId.'],
            'merchantTransactionId' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],
            'statusURL' => ['statusURL', 'Mandatory parameters are missing. Please ensure you provide:  statusURL.'],
            'successURL' => ['successURL', 'Mandatory parameters are missing. Please ensure you provide:  successURL.'],
            'errorURL' => ['errorURL', 'Mandatory parameters are missing. Please ensure you provide:  errorURL.'],
            'cancelURL' => ['cancelURL', 'Mandatory parameters are missing. Please ensure you provide:  cancelURL.'],
            'awaitingURL' => ['awaitingURL', 'Mandatory parameters are missing. Please ensure you provide:  awaitingURL.'],
            'firstName' => ['firstName', 'Mandatory parameters are missing. Please ensure you provide:  firstName.'],
            'dob' => ['dob', 'Mandatory parameters are missing. Please ensure you provide:  dob.'],
            'ipAddress' => ['ipAddress', 'Mandatory parameters are missing. Please ensure you provide:  ipAddress.'],
            'lastName' => ['lastName', 'Mandatory parameters are missing. Please ensure you provide:  lastName.'],
            'billingFirstName' => ['billingFirstName', 'Mandatory parameters are missing. Please ensure you provide:  BillingFirstName.'],
            'billingLastName' => ['billingLastName', 'Mandatory parameters are missing. Please ensure you provide:  BillingLastName.'],
            'billingAddress' => ['billingAddress', 'Mandatory parameters are missing. Please ensure you provide:  BillingAddress.'],
            'itemName' => ['itemName', 'Mandatory parameters are missing. Please ensure you provide:  Item name.'],
            'category' => ['category', 'Mandatory parameters are missing. Please ensure you provide:  Item category.'],
            'reference' => ['reference', 'Mandatory parameters are missing. Please ensure you provide:  Item reference.'],
            'unitPriceWithTax' => ['unitPriceWithTax', 'Mandatory parameters are missing. Please ensure you provide:  Item unitPriceWithTax.'],
            'units' => ['units', 'Mandatory parameters are missing. Please ensure you provide:  Item units.'],
            'totalPriceWithTax' => ['totalPriceWithTax', 'Mandatory parameters are missing. Please ensure you provide:  Item totalPriceWithTax.'],
            'product' => ['product', 'Mandatory parameters are missing. Please ensure you provide:  Product.'],
            'billing' => ['billing', 'Mandatory parameters are missing. Please ensure you provide:  Billing.'],
            'cartCurrency' => ['cartCurrency', 'Mandatory parameters are missing. Please ensure you provide:  CartCurrency.'],
            'paysolExtendedData' => ['paysolExtendedData', 'Mandatory parameters are missing. Please ensure you provide:  paysolExtendedData.'],

        ];
    }


}