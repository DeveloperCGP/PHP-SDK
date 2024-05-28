<?php

namespace AddonPaymentsSDK\Config\PaySolExtended;

use AddonPaymentsSDK\Config\PaySolExtended\Items\FlightItem;

class FlightTransaction extends BaseTransaction
{


    /**
     * Add a flight item to the cart.
     * 
     * @param FlightItem $item The flight item to add to the cart.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addItem(FlightItem $item): self
    {
        $item->validate();
        $this->data['cart']['items'][] = $item->getItem();
        return $this;
    }


}
