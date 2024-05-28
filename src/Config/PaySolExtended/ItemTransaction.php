<?php

namespace AddonPaymentsSDK\Config\PaySolExtended;

use AddonPaymentsSDK\Config\PaySolExtended\Items\ProductItem;

class ItemTransaction extends BaseTransaction
{


   
    

    /**
     * Set the shipping address.
     * 
     * @param string $streetAddress The street address.
     * @param string $streetAddress2 Additional street address information.
     * @param int $postalCode The postal code.
     * @param string $city The city.
     * @param string $country The country.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingAddress(string $streetAddress, string $streetAddress2, int $postalCode, string $city, string $country): self
    {
        $this->data['shipping']['address'] = [
            'street_address' => $streetAddress,
            'street_address_2' => $streetAddress2,
            'postal_code' => $postalCode,
            'city' => $city,
            'country' => $country
        ];
        return $this;
    }

    /**
     * Add a product item to the cart.
     * 
     * @param ProductItem $item The product item to add to the cart.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addItem(ProductItem $item): self
    {
        $item->validate();
        $this->data['cart']['items'][] = $item->getItem();
        return $this;
    }







}
