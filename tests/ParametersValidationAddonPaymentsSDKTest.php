<?php
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;

class ParametersValidationAddonPaymentsSDKTest extends TestCase
{
     

    public function testAmountValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setAmount('Invalid');
    }


    public function testNegativeAmountValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setAmount(-30);
    }

    public function testCurrencyValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setCurrency('Invalid');
    }

    public function testCountryValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setCountry('Invalid');
    }

    public function testCustomerIdValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setCustomerId('qweqweqweqweqweqweqweqweqweqweqweqweqweasdzsdadasdzxczxczxczxczxczxczxczxczxczxczxczxczxcasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd');
    }

    public function testPaymentSolutionValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setPaymentSolution('Invalid');
    }

    public function testMerchantTransactionIdValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setMerchantTransactionId('qweqweqweqweqweqweqweqweqweqweqweqweqweasdzsdadasdzxczxczxczxczxczxczxczxczxczxczxczxczxcasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd');
    }

    public function testPaymentRecurringValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setPaymentRecurringType('Invalid');
    }



    public function testCardNumberValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setCardNumber('1234567812345678');
    }

    public function testExpDateValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setExpDate('Invalid');
    }

    public function testStatusUrlValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setStatusURL('Invalid');
    }

    public function testSuccessUrlValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setSuccessURL('Invalid');
    }

    public function testErrorUrlValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setErrorURL('Invalid');
    }


    public function testCancelURLValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setCancelURL('Invalid');
    }

    public function testAwaitingURLValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setAwaitingURL('Invalid');
    }

    public function testDobValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setDob('Invalid');
    }


    public function testCustomerEmailValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $parameters = new Parameters();
        $parameters->setCustomerEmail('Invalid');
    }

  

   

    public function testLanguageValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new Parameters();
        $parameters->setLanguage('Invalid');
    }



   

    

}