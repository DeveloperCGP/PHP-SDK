<?php
include __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use AddonPaymentsSDK\Config\Parameters\QuixParameters;
use AddonPaymentsSDK\Config\PaySolExtended\ItemTransaction;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;
use AddonPaymentsSDK\Config\PaySolExtended\Items\ServiceItem;
use AddonPaymentsSDK\Config\PaySolExtended\Items\FlightItem;

use AddonPaymentsSDK\Config\PaySolExtended\Items\AccommodationItem;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
class QuixValidationAddonPaymentsSDKTest extends TestCase
{
    public function testBillingValidation()
    {
        $this->expectException(\TypeError::class);
        $transaction = new ItemTransaction();
        $transaction->setBilling('invalid');

    }

    public function testCartCurrencyValidation()
    {
        $this->expectException(\TypeError::class);
        $transaction = new ItemTransaction();
        $transaction->setCartCurrency('invalid');

    }

    public function testProductItemReferenceValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new ProductItem();
        $item->setReference('invalid');


    }

    public function testProductItemUnitPriceWithTaxValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new ProductItem();
        $item->setUnitPriceWithTax('invalid');


    }

    public function testProductItemUnitsValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new ProductItem();
        $item->setUnits('invalid');


    }

    public function testProductItemTotalPriceWithTaxValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new ProductItem();
        $item->setTotalPriceWithTax('invalid');


    }

    public function testAddItemValidation()
    {
        $this->expectException(\TypeError::class);
       $transaction = new ItemTransaction();
       $transaction->addItem('invalid');


    }

    public function testPaysolExtendedDataValidation()
    {
        $this->expectException(\TypeError::class);
        $parameters = new QuixParameters();
        $parameters->setPaysolExtendedData('invalid');


    }

    public function testServiceItemStartDateValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $item = new ServiceItem();
        $item->setStartDate('invalid');


    }

    public function testServiceItemEndDateValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $item = new ServiceItem();
        $item->setEndDate('invalid');


    }

    public function testFlightItemDepartureDateValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $item = new FlightItem();
        $item->setDepartureDate('invalid');


    }

    public function testFlightItemPassengerValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new FlightItem();
        $item->addPassenger('invalid');


    }

    public function testFlightItemSegmentValidation()
    {
        $this->expectException(\TypeError::class);
        $item = new FlightItem();
        $item->addSegment('invalid');


    }


    public function testAccommodationItemCheckinDatetValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $item = new AccommodationItem();
        $item->setCheckinDate('invalid');


    }

    public function testAccommodationItemCheckoutDatetValidation()
    {
        $this->expectException(InvalidFieldException::class);
        $item = new AccommodationItem();
        $item->setCheckoutDate('invalid');


    }



}