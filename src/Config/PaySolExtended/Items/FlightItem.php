<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items;

use AddonPaymentsSDK\Config\PaySolExtended\Items\Utils\Passenger;
use AddonPaymentsSDK\Config\PaySolExtended\Items\Utils\Segment;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
class FlightItem extends Item
{
    use LoggerTrait;
    public function __construct()
    {
        parent::__construct();
        $this->setType('flight');
    }

    /**
     * Set the departure date for the flight.
     * 
     * @param string $departureDate The departure date in the format YYYY-MM-DDTHH:mm:ss+HH:mm.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException If the departure date is not in the correct format.
     */
    public function setDepartureDate(string $departureDate): self
    {
        if (!$this->isValidDateTime($departureDate)) {
            $this->logError("Flight Departure eDate is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
            throw new InvalidFieldException("Flight Departure eDate is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
        }
        $this->item['article']['departure_date'] = $departureDate;
        return $this;
    }

    /**
     * Add a passenger to the flight item.
     * 
     * @param Passenger $passenger The passenger object to add.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addPassenger(Passenger $passenger): self
    {
        $passenger->validate();
        $this->item['article']['passengers'][] = $passenger->getPassenger();
        return $this;
    }

    /**
     * Add a segment to the flight item.
     * 
     * @param Segment $segment The segment object to add.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addSegment(Segment $segment): self
    {
        $this->item['article']['segments'][] = $segment->getSegment();
        return $this;
    }


   
    private function isValidDateTime(mixed $value): bool
    {

        $format = 'Y-m-d\\TH:i:sP'; // Format to match the provided example
        $dateTime = \DateTime::createFromFormat($format, $value);

        return $dateTime && $dateTime->format($format) === $value;
    }

    /**
     * @return void
     */
    public function validate()
    {


        $requiredKeys = [
            'article' => [
                'label' => 'Article',
                'keys' => [
                    'category' => 'Item category',
                    'reference' => 'Item reference',
                    'unit_price_with_tax' => 'Item unitPriceWithTax',
                    'passengers' => 'Item passengers',
                    'segments' => 'Item segments'
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