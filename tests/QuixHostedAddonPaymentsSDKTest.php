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
            ->setCustomerEmail('test@mail.com')
            ->setMerchantTransactionId('87315')
            ->setCustomerNationalId('99999999R')
            ->setIpAddress('192.168.1.1')
            ->setStatusURL('https://test.com/status')
            ->setSuccessURL('https://test.com/status')
            ->setErrorURL('https://test.com/status')
            ->setCancelURL('https://test.com/status')
            ->setAwaitingURL('https://test.com/status')
            ->setCustomerNationalId('99999999R')
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
        
        $expectedEncryptedRequest = 'g4lqI0iz6/VzgHQjJK3WxQDlLXnEcmAjhRUZRNecq7v0G157k8a8tyP+/uVrxrqENLr6fzqOPRY+3RxnkctcdFLPkJnZdGCOJn4QxDamZcchawj7nfA4wfXwwP3uU85TghmqJDvsl7xlfn48iGG932mKivaYO6YtXQpx1vijyML5CMaDZguFxoc/QmBc+gWXHxEqvehAFrYOmbMtQLo0CDo/doiDIwnvJNZrKEYxvP3TzExIxK+2lDKO3VlhEHhIM3/gptXtkhz+rQQfwu0d8hsWqlRs9gY1xqTk6VZ4HHfgU8BWQrqafFYGV5XtnoabKDaeqVs4pyt7SVSpmWq5uEUrF+oYvtdS3qeMxoZSqgrUj74VLehqREKGpI204aTxm2BnKGGpwpyoZiYGYia6+XtenieaauXEf+Vj902jzKWRsT3NpRYZeeoaTj2/bCqSfHo5Sur3lxQyCXqhG+BiULkLYGulbGazgbysbION0bArIOQn0CXpBr2FGeBBKECuwQI3t8ViF95rVSDVfZIbDa/x+WmKT3mF81bTx1+qBKKnCpg39DOcPEV2PmoEDj5UMB2hLckQlDhJpGvocuNrAMoFJTbCraTygWzUCFMzfpf0FCgfplxnpKJ8cYj6arbvoP0GmPnWg2cafFbBivPwIyodHvKxPILHORPYUouie4itxhV9zbohUj3AHfxBLBlGQbkrcYFSEp2bWkPGxsI7CBRcKsZ+MmO003OKQjLRDoPXqvx4lVcfBTB1TPbVtDGE7xZ6GF8ZCJpms5OUZEmcZvPvJaFdayXUh5247L6NGvaPyeFAE75F6xULroGoEPo46Zk4oSvjsFZMqTNHhf3k+8CseNVwmPtS9qVbSJNMZdsWSOnuyLZyxbKxMg7uuy1oYx8RRfRHRKLoa/AgxdhWlxsbcGwoolgyvfVAHgqA2/RioYfoVd5a69El+n48H3iAVXUPopMFlywYNZ6Cp3eboAD62WzQJMGelpmYk7v1PgFLTqMbCFB2nAnQC4c+RG3fuN63LBuvnw9FvYl7ebzBcmpAeA4MDiLoE4TxBaB8mBqU12L0odzxX4pMIvYqBqE60Pye2k6pCvM5XUZNdMvSM79GOKYTPLJotUmwMwlEuZ5CBI8x8qCPPHVhxsnNnfswaj1X6hsG9gq44FK8XkI37aXAz7QeaUleDTTa9IDvE61OIKmK8E31y9adb9QdVTel7CP5WXdgW2P+vJTvjrNy1eIowTRsRuzKLAYfZmSslRAKwIk1GuRyJWyMcOosFMw9MjI3LJlFs91vzpliPML4xhet6Ae0PH+9pjvrSPL24x4iyEAkdH+Gh26LjpsyfjUyzNjpJQcmfXe9KLZo+COtb6caVJgwVnZlQas0ImzZrfJ/tJVRPpJd6ZMVWFI0QTTwrt6hYRpHv1eOHkMXbVIzL/AImMLPLacttykqU7InyIvho1VUNKcHlz1SJTh6vMp2HaeFIqsjsV8zsvJFDCc9d2PQ4MmDDwOIIbbhblbMFpjlR+WWOPd+AnxjJChJqL7vSnT4JmUx1Zr9sawwKlSO7U6D4p/EWnNLuEGao0e7zTF/NKyUzfsS6DK5DYQPSq6Wvg1mZ9Norc4yySh1Q48SEmchTnkBlCWUzs/j8HWorykBFHEw7n0xB0SdfA9DB0s2HHNYoTwVBZpeVDxj08dWKA==';

        $this->assertEquals($expectedEncryptedRequest, $encryptedRequest, 'The encrypted request does not match the expected values.');
        $expectedFormatedRequest = 'paymentSolution=quix&language=ES&currency=EUR&country=ES&customerCountry=ES&customerId=11&customerEmail=test%40mail.com&merchantTransactionId=87315&customerNationalId=99999999R&ipAddress=192.168.1.1&statusURL=https%3A%2F%2Ftest.com%2Fstatus&successURL=https%3A%2F%2Ftest.com%2Fstatus&errorURL=https%3A%2F%2Ftest.com%2Fstatus&cancelURL=https%3A%2F%2Ftest.com%2Fstatus&awaitingURL=https%3A%2F%2Ftest.com%2Fstatus&firstName=Nombre&dob=01-12-1999&lastName=Apellido&paysolExtendedData=%7B%22product%22%3A%22instalments%22%2C%22billing%22%3A%7B%22first_name%22%3A%22Nombre%22%2C%22last_name%22%3A%22Apellido+SegundoApellido%22%2C%22address%22%3A%7B%22street_address%22%3A%22Nombre+de+la%22%2C%22postal_code%22%3A28003%2C%22city%22%3A%22Barcelona%22%2C%22country%22%3A%22ESP%22%7D%7D%2C%22cart%22%3A%7B%22currency%22%3A%22EUR%22%2C%22items%22%3A%5B%7B%22auto_shipping%22%3Atrue%2C%22article%22%3A%7B%22type%22%3A%22product%22%2C%22name%22%3A%22Item+1%22%2C%22category%22%3A%22physical%22%2C%22reference%22%3A4912345678904%2C%22unit_price_with_tax%22%3A300%7D%2C%22units%22%3A1%2C%22total_price_with_tax%22%3A300%7D%5D%2C%22total_price_with_tax%22%3A300%7D%7D&amount=300&productId=111166625&merchantParams=sdk%3Aphp%3Bversion%3A1.0.2%3Btype%3AHosted&merchantId=1111111';

        $this->assertEquals($expectedFormatedRequest, $formatedRequest, 'The formatted request does not match the expected values.');
        $this->assertContains('sdk:php;version:1.0.2;type:Hosted', $merchantParams, 'Assert merchantParams in request');

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
            'customerNationalId' => ['customerNationalId', 'Mandatory parameters are missing. Please ensure you provide:  customerNationalId.'],
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