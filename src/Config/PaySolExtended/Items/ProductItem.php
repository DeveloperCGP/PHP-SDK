<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
class ProductItem extends Item
{
    use LoggerTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setType('product');
    }

    /**
     * Validate the product item data.
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
                    'unit_price_with_tax' => 'Item unitPriceWithTax'
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