<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items;

use AddonPaymentsSDK\Config\Enums\Category;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
abstract class Item
{
    use LoggerTrait;
    protected array $item = [];

    public function __construct() {
        $this->item['auto_shipping'] = true;
    }

    /**
     * Set the name of the item.
     * 
     * @param string $name The name of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setName(string $name): self
    {
        $this->item['article']['name'] = $name;
        return $this;
    }

    /**
     * Set the type of the item.
     * 
     * @param string $type The type of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setType(string $type): self
    {
        $this->item['article']['type'] = $type;
        return $this;
    }

    /**
     * Set the category of the item.
     * 
     * @param Category $category The category of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCategory(Category $category): self
    {
        $this->item['article']['category'] = $category;
        return $this;
    }

    /**
     * Set the brand of the item.
     * 
     * @param string $brand The brand of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBrand(string $brand): self
    {
        $this->item['article']['brand'] = $brand;
        return $this;
    }

    /**
     * Set the description of the item.
     * 
     * @param string $brand The brand of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setDescription(string $description): self
    {
        $this->item['article']['description'] = $description;
        return $this;
    }

    /**
     * Set the MPN (Manufacturer Part Number) of the item.
     * 
     * @param string $mpn The MPN of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setMPN(string $mpn): self
    {
        $this->item['article']['mpn'] = $mpn;
        return $this;
    }

    /**
     * Set the image URL of the item.
     * 
     * @param string $imageUrl The image URL of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setImageUrl(string $imageUrl): self
    {
        $this->item['article']['image_url'] = rawurlencode($imageUrl);
        return $this;
    }

    /**
     * Set the URL of the item.
     * 
     * @param string $url The URL of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setURL(string $url): self
    {
        $this->item['article']['url'] = rawurlencode($url);
        return $this;
    }

    /**
     * Set the reference of the item.
     * 
     * @param int $reference The reference of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setReference(int $reference): self
    {
        $this->item['article']['reference'] = $reference;
        return $this;
    }

    /**
     * Set the total discount of the item.
     * 
     * @param float $totalDiscount The total discount of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setTotalDiscount(float $totalDiscount): self
    {
        $this->item['article']['total_discount'] = $totalDiscount;
        return $this;
    }

    /**
     * Set the unit price with tax of the item.
     * 
     * @param float $unitPrice The unit price with tax of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setUnitPriceWithTax(float $unitPrice): self
    {
        $this->item['article']['unit_price_with_tax'] = $unitPrice;
        return $this;
    }

    /**
     * Set the number of units of the item.
     * 
     * @param int $units The number of units of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setUnits(int $units): self
    {
        $this->item['units'] = $units;
        return $this;
    }


    /**
     * Set the total price with tax of the item.
     * 
     * @param float $totalPrice The total price with tax of the item.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setTotalPriceWithTax(float $totalPrice): self
    {
        $this->item['total_price_with_tax'] = $totalPrice;
        return $this;
    }

    /**
     * Set whether the item has auto shipping.
     * 
     * @param bool $autoShipping Whether the item has auto shipping.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setAutoShipping(bool $autoShipping): self
    {
        $this->item['auto_shipping'] = $autoShipping;
        return $this;
    }

    /**
     * Get the item data as an array.
     * 
     * @return array Returns the item data.
     */
    public function getItem(): array
    {
        return $this->item;
    }

    /**
     * Get the total price with tax of the item.
     * 
     * @return float Returns the total price with tax.
     */
    public function getTotalPriceWithTax(): float
    {
        return $this->item['total_price_with_tax'];
    }

    /**
     * Validate the item data.
     * 
     * @return void
     */
    public function validate()
    {


        $requiredKeys = [
            'units' => 'Item units',
            'total_price_with_tax' => 'Item totalPriceWithTax',
            'article' => [
                'label' => 'Article',
                'keys' => [
                    'name' => 'Item name',
                    'reference' => 'Item reference',
                  
                ]
            ],
          
        ];



        $missingKeys = [];
    
        foreach ($requiredKeys as $key => $value) {
            if (is_array($value)) {
                if (!isset($this->item[$key]) || empty($this->item[$key])) {
                    $missingKeys[] = $value['label'];
                } else {
                    foreach ($value['keys'] as $subKey => $subLabel) {
                        if (!isset($this->item[$key][$subKey]) || $this->item[$key][$subKey] == '') {
                            $missingKeys[] = $subLabel;
                        }
                    }
                }
            } else {
                if (!isset($this->item[$key]) || $this->item[$key] == '') {
                    $missingKeys[] = $value;
                }
            }
        }

        
        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys)  . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys)  . '.');
        }
    }



}