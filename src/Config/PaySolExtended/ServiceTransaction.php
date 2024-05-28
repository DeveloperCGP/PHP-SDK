<?php

namespace AddonPaymentsSDK\Config\PaySolExtended;

use AddonPaymentsSDK\Config\PaySolExtended\Items\ServiceItem;

class ServiceTransaction extends BaseTransaction
{


    /**
     * Add a service item to the cart.
     * 
     * @param ServiceItem $item The service item to add to the cart.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addItem(ServiceItem $item): self
    {
        $item->validate();
        $this->data['cart']['items'][] = $item->getItem();
        return $this;
    }



}
