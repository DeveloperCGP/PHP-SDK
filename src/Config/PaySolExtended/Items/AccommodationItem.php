<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
class AccommodationItem extends Item
{
    use LoggerTrait;
    public function __construct()
    {
        parent::__construct();
        $this->setType('accommodation');
    }

    /**
     * Set the check-in date for the accommodation.
     * 
     * @param string $checkInDate The check-in date in the format YYYY-MM-DDTHH:mm:ss+HH:mm.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException If the check-in date is not in the correct format.
     */
    public function setCheckinDate(string $checkInDate): self
    {
        if (!$this->isValidDateTime($checkInDate)) {
            $this->logError("Accommodation Check in Date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
            throw new InvalidFieldException("Accommodation Check in Date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
        }

        $this->item['article']['checkin_date'] = $checkInDate;
        return $this;
    }

    /**
     * Set the check-out date for the accommodation.
     * 
     * @param string $checkOutDate The check-out date in the format YYYY-MM-DDTHH:mm:ss+HH:mm.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException If the check-out date is not in the correct format.
     */
    public function setCheckoutDate(string $checkOutDate): self
    {
        if (!$this->isValidDateTime($checkOutDate)) {
            $this->logError("Accommodation Check out Date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
            throw new InvalidFieldException("Accommodation Check out Date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
        }
        $this->item['article']['checkout_date'] = $checkOutDate;
        return $this;
    }

    /**
     * Set the name of the accommodation establishment.
     * 
     * @param string $establishmentName The name of the establishment.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setEstablishmentName(string $establishmentName): self
    {
        $this->item['article']['establishment_name'] = $establishmentName;
        return $this;
    }

    /**
     * Set the number of guests for the accommodation.
     * 
     * @param int $guests The number of guests.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setGuests(int $guests): self
    {
        $this->item['article']['guests'] = $guests;
        return $this;
    }


    /**
     * Set the address of the accommodation.
     * 
     * @param string $streetAddress The street address of the accommodation.
     * @param int $postalCode The postal code of the accommodation.
     * @param string $city The city of the accommodation.
     * @param string $country The country of the accommodation.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setAddress(string $streetAddress, int $postalCode, string $city, string $country): self
    {
        $this->item['article']['address'] = [
            'street_address' => $streetAddress,
            'postal_code' => $postalCode,
            'city' => $city,
            'country' => $country
        ];
        return $this;
    }

    function isValidDateTime(mixed $value): bool
    {

        $format = 'Y-m-d\\TH:i:sP'; // Format to match the provided example
        $dateTime = \DateTime::createFromFormat($format, $value);

        return $dateTime && $dateTime->format($format) === $value;
    }

    /**
     * Validate the accommodation item data.
     * 
     * @return void
     * @throws InvalidFieldException If any required fields are missing.
     */
    public function validate()
    {

        parent::validate();


        $requiredKeys = [
            'article' => [
                'label' => 'Article',
                'keys' => [
                    'category' => 'Item category',
                    'reference' => 'Item reference',
                    'unit_price_with_tax' => 'Item unitPriceWithTax',
                    'checkout_date' => 'Item checkoutDate',
                    'checkin_date' => 'Item checkinDate',
                    'establishment_name' => 'Item establishmentName',
                    'address' => 'Item address',
                    'guests' => 'Item guests'
                ]
            ]
        ];

        
        $missingKeys = [];
        
        foreach ($requiredKeys as $key => $value) {
            if (!isset($this->item[$key]) || empty($this->item[$key])) {
                $missingKeys[] = $value['label'];
            } else {
                foreach ($value['keys'] as $subKey => $subLabel) {
                    if (!isset($this->item[$key][$subKey]) || $this->item[$key][$subKey] == '') {
                        $missingKeys[] = $subLabel;
                    }
                }
            }
        }


        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }


        
    }

}