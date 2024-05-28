<?php

namespace AddonPaymentsSDK\Config\PaySolExtended;
use AddonPaymentsSDK\Config\PaySolExtended\Items\AccommodationItem;

class AccommodationTransaction extends BaseTransaction {
   

   /**
     * Add an accommodation item to the cart.
     * 
     * @param AccommodationItem $item The accommodation item to add to the cart.
     * @return self Returns the instance of the class for method chaining.
     */
    public function addItem(AccommodationItem $item): self
    {
        $item->validate();
        $this->data['cart']['items'][] = $item->getItem();
        return $this;
    }
   

}
