<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

class ServiceItem extends Item {
    use LoggerTrait;
    public function __construct() {
        parent::__construct();
        $this->setType('service');
     }

      /**
     * Set the start date of the service item.
     * 
     * @param mixed $startDate The start date of the service item in YYYY-MM-DDTHH:mm:ss+HH:mm format.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided start date format is invalid.
     */
    public function setStartDate(mixed $startDate) : self {
        
        if (!$this->isValidDateTime($startDate)) {
            $this->logError("Item start date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
            throw new InvalidFieldException("Item start date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
        }
        $this->item['article']['start_date'] = $startDate;
        return $this;
    }

     /**
     * Set the end date of the service item.
     * 
     * @param mixed $endDate The end date of the service item in YYYY-MM-DDTHH:mm:ss+HH:mm format.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException if the provided end date format is invalid.
     */
    public function setEndDate(mixed $endDate) : self {
        if (!$this->isValidDateTime($endDate)) {
            $this->logError("Item end date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
            throw new InvalidFieldException("Item end date is not valid it should be in YYYY-MM-DDTHH:mm:ss+HH:mm Format.");
        }
        $this->item['article']['end_date'] = $endDate;
        return $this;
    }

    /**
     * Check if a value is a valid date and time in the specified format.
     * 
     * @param mixed $value The value to check.
     * @return bool Returns true if the value is a valid date and time in the specified format, false otherwise.
     */
    function isValidDateTime(mixed $value): bool {
        
        $format = 'Y-m-d\\TH:i:sP'; // Format to match the provided example
        $dateTime = \DateTime::createFromFormat($format, $value);
       
        return $dateTime && $dateTime->format($format) === $value;
    }

    /**
     * Validate the service item data.
     * 
     * @return void
     * @throws InvalidFieldException if any required fields are missing.
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
                    'end_date' => 'Item endDate'
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