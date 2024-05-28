<?php
use AddonPaymentsSDK\Requests\CreateRedirectionRequest;
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\AddonPaymentsSDK;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\ItemTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;
use AddonPaymentsSDK\Config\Credentials;
use AddonPaymentsSDK\Config\Enums\CountryCodes;
use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
use AddonPaymentsSDK\Config\Enums\Category;
class QuixHostedAddonPaymentsSDKTest extends TestCase
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
        $parameters = new QuixParameters();
        $parameters
            ->setCustomerId('11')
            ->setCustomerCountry(CountryCodes::ES)
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
            ->setBillingAddress('Nombre de la', '28003', 'Barcelona', 'ESP');
        $transaction = new ItemTransaction();
        $transaction->setProduct('instalments')
            ->setBilling($billing)
            ->setCartCurrency(CurrencyCodes::EUR);
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
        $addonPaymentsSDK = new AddonPaymentsSDK($config);

        $ivGeneratorMock = function () use ($fixedIv) {
            return $fixedIv;
        };

        // Step 2: Create a mock object for CreateRedirectionRequest
        $createRedirectionRequestMock = $this->getMockBuilder(CreateRedirectionRequest::class)
            ->onlyMethods(['requestCurl']) // Mock only requestCurl method
            ->setConstructorArgs([$ivGeneratorMock])
            ->getMock();
        $createRedirectionRequestMock->method('requestCurl')->willReturn(['response' => $reponseMock, 'status_code' => 200, 'message' => null]);

        // Step 3: Use reflection to replace the createRequest property
        $reflectionClass = new ReflectionClass($addonPaymentsSDK);
        $property = $reflectionClass->getProperty('createRequest'); // Adjust the property name if it's different
        $property->setAccessible(true); // Make the property accessible
        $property->setValue($addonPaymentsSDK, $createRedirectionRequestMock); // Inject the mock

        // Step 4: Proceed with your test as normal
        $sdk = $addonPaymentsSDK;
        $sendRequest = $sdk->sendQuixRedirectionPaymentRequest();
        $formatedRequest = $sendRequest->getFormattedReq();
        $encryptedRequest = $sendRequest->getEncryptedRequest();
        $merchantParams = $sendRequest->getOtherConfigurations();
        $paymentLink = $sendRequest->getResponse()->getRedirectUrl();
        
        $expectedEncryptedRequest = 'g4lqI0iz6/VzgHQjJK3WxQDlLXnEcmAjhRUZRNecq7v0G157k8a8tyP+/uVrxrqENLr6fzqOPRY+3RxnkctcdBQ9HHk9e9Hs3ot3japWb8ESTUqEoKXpkGf4cNmx2ypjeniuaWO0ts7HGkdXR16tNpdNmMGcdXXnbD9+2miqpnqc1VjPHfREZzlZzzHpckJ3lHAJhN7ublSYr6fKErii27ISoko0ubGHAukK5Pq8bnQi4iCo6M9szkgJS3kJzWrHnTxUSywsDtbkjotACKAlJ3otxdhxGYw1a4MWvUYAWkdng5pijjjMNLF1lMeBd5FxJJY9JkZJ05iAh38M6/3/3ZgM7drA0DzG/Z4GQb7DAQV+pfPkXkP48w8V1uqe8KH++Muavklr/UIDDGk3kXMavwxmCuWUSnUvLMQdFJwmZuiSsKaXODXH2Gaucnq0vgPvX1BnaJc0LcTa0tWm8kdvwnWMGv4iFnW22mSYRl7kEpop4x3eVLMaFuSb98pzMA0hmgL6GMVydgCFVQOF6AJY1PIEoMQwGVbpcTuGEsDkSd7prxnwhLYVxBBlXoumjwku5McqoGkbD2E5Y99wAe6ykiddi0aInSyou3eLmn6Nhkq1GcfuD4qVi/6X4lFkK5wWOp6gF2DrkzN7cGS0wsqr7yfUtIIBnqU4uFWkyaPc1cZ/axO2ZYKWx+lJ44AL8v5sWAlGTYxm4YrOICUAs8RYlF4aRHXNWSYYDtpbkSbd5EYIxDpCLL7UUdoDULHWNpj9o+dJNCFNCY4/HLuAl7s3pDmfHhTZVb7+k/vwHI/AOkE2zrQ7qgUstk6fvhdX75YP2AqRnEqhYRA86A2vhv4EJ4swCw7oCdNsnVxFO4bMpC5U6/53DXBEKPSa0zGNuVk75IewVnr4i04ByozIXCt1IUl5gdkEk9UlE2dabR5YlPANindDst3CG9q8S+lv/yXQgAn+HEIIpFvfGI02HcFRoJh/Fsk+Z/yQGb1kdZguIlav9E+DPqar1qTlZqh/O34Wq+aBTL5mZOh4v57ZL4RTxFvA/nKIxSDuJJkXH4vZRDrn4twcyzLNIXoxOnApb424UOte9lcueoATn/dWXhjFo/5KaYGU8D0gDRTLfo6eS3prmOnU0GBOx/Te59pduz+Tac2oDiWOPye3dHbKlKPjeeXTbc5As5E0inaUKfdhZy3duOjORyceWHdPGBFnxBii9b5tLNQIu2o0VW4uVHA2EQKNJS9OBdYXl9u/ubPIN9OelDUWKD4O2YbrJi5moPGk+o/HZlzAIk8k667i9zBxymMWW7v8TC57gPJjlZVccL4voDX2Ka+67jsfGQb/m1HAbybKXlG2AYcUrczpToEwCKedpWxSIopvz7lwo7p3egOhgTDRF/qer8eo+cSlHdg/SOMeEGR0waJPDynF+MKiyhEa6qmC/Wey50cgJ5qT0j4mOwIL0YCBPYKplW0KQzWOuHRjwvD4rcoSrVBa76RPinrLlvxtxb593zkLM+PEOmMGqBYuLgwrGnxiY9ibM/hwh6G54M09uZCucPOs9ENy5g4D6edrMm39IITs0d/+MhMS/x5gUSjxT2bm7PSnjKU0k7bRF/l6wKbPNiaaMlIRdlKSY3VPzwU+ON+zEG0ueGmrn8bE1Pu167ilbPitrnhr+2LcH6gra11ZfP81Ek4B0S1nFZOsiPs4fxkIYz6RwioBSEJYZNcxsHs0LzBqVzl7k0dlrXzd/KTEnxcWYJMfoQ==';

        $this->assertEquals($expectedEncryptedRequest, $encryptedRequest, 'The encrypted request does not match the expected values.');
        $expectedFormatedRequest = 'paymentSolution=quix&language=ES&currency=EUR&country=ES&customerCountry%5Bname%5D=ES&customerCountry%5Bvalue%5D=ES&customerId=11&customerEmail=test%40mail.com&merchantTransactionId=87315&customerNationalId=99999999R&ipAddress=192.168.1.1&statusURL=https%3A%2F%2Ftest.com%2Fstatus&successURL=https%3A%2F%2Ftest.com%2Fstatus&errorURL=https%3A%2F%2Ftest.com%2Fstatus&cancelURL=https%3A%2F%2Ftest.com%2Fstatus&awaitingURL=https%3A%2F%2Ftest.com%2Fstatus&firstName=Nombre&dob=01-12-1999&lastName=Apellido&paysolExtendedData=%7B%22product%22%3A%22instalments%22%2C%22billing%22%3A%7B%22first_name%22%3A%22Nombre%22%2C%22last_name%22%3A%22Apellido+SegundoApellido%22%2C%22address%22%3A%7B%22street_address%22%3A%22Nombre+de+la%22%2C%22postal_code%22%3A28003%2C%22city%22%3A%22Barcelona%22%2C%22country%22%3A%22ESP%22%7D%7D%2C%22cart%22%3A%7B%22currency%22%3A%22EUR%22%2C%22items%22%3A%5B%7B%22auto_shipping%22%3Atrue%2C%22article%22%3A%7B%22type%22%3A%22product%22%2C%22name%22%3A%22Item+1%22%2C%22category%22%3A%22physical%22%2C%22reference%22%3A4912345678904%2C%22unit_price_with_tax%22%3A300%7D%2C%22units%22%3A1%2C%22total_price_with_tax%22%3A300%7D%5D%2C%22total_price_with_tax%22%3A300%7D%7D&amount=300&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.00%3Btype%3AHosted&merchantId=1111111';

        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');
        $this->assertContains('sdk:php;version:1.00;type:Hosted', $merchantParams, 'Assert merchantParams in request');

        // Your existing assertions...
        $urlPattern = '/https:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(\/\S*)?/';
        $this->assertMatchesRegularExpression($urlPattern, $paymentLink, 'Test for verifying URL presence in sendRedirectionPaymentRequest');
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
        if ($missingParameter !== 'merchantPassword')
            $cred->setMerchantPassword(getenv('MERCHANT_PASS'));
        if ($missingParameter !== 'productId')    
            $cred->setProductId(getenv('PRODUCT_ID_PRODUCTS'));
        if ($missingParameter !== 'environment')    
            $cred->setEnvironment(Environment::STAGING);    


        $parameters = new QuixParameters();
        if ($missingParameter !== 'customerId')
            $parameters->setCustomerId('11');
        if ($missingParameter !== 'customerEmail')
            $parameters->setCustomerEmail('test@mail.com');
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

        $addonPaymentsSDK->sendQuixRedirectionPaymentRequest();
    }

    public static function missingQuixParameterProvider()
    {
        return [
            'merchantId' => ['merchantId', 'Mandatory credentials are missing. Please ensure you provide:  merchantId.'],
            'merchantPassword' => ['merchantPassword', 'Mandatory credentials are missing. Please ensure you provide:  merchantPassword.'],
            'Missing Product Id' => ['productId', 'Mandatory credentials are missing. Please ensure you provide:  productId.'],
            'Missing Environment' => ['environment', 'Mandatory credentials are missing. Please ensure you provide:  environment.'],
            
            'customerId' => ['customerId', 'Mandatory parameters are missing. Please ensure you provide:  customerId.'],
            'customerEmail' => ['customerEmail', 'Mandatory parameters are missing. Please ensure you provide:  customerEmail.'],
            'merchantTransactionId' => ['merchantTransactionId', 'Mandatory parameters are missing. Please ensure you provide:  merchantTransactionId.'],

            'statusURL' => ['statusURL', 'Mandatory parameters are missing. Please ensure you provide:  statusURL.'],
            'successURL' => ['successURL', 'Mandatory parameters are missing. Please ensure you provide:  successURL.'],
            'errorURL' => ['errorURL', 'Mandatory parameters are missing. Please ensure you provide:  errorURL.'],
            'cancelURL' => ['cancelURL', 'Mandatory parameters are missing. Please ensure you provide:  cancelURL.'],
            'awaitingURL' => ['awaitingURL', 'Mandatory parameters are missing. Please ensure you provide:  awaitingURL.'],
            'firstName' => ['firstName', 'Mandatory parameters are missing. Please ensure you provide:  firstName.'],
            'ipAddress' => ['ipAddress', 'Mandatory parameters are missing. Please ensure you provide:  ipAddress.'],
            'dob' => ['dob', 'Mandatory parameters are missing. Please ensure you provide:  dob.'],
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