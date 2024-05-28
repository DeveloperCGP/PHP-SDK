<?php
namespace AddonPaymentsSDK\Config\Enums;

enum Category : string{
  
    case PHYSICAL = "physical";
    case DIGITAL = "digital";

    case GIFT_CARD = "gift_card";
    case DISCOUNT = "discount";
    case SHIPPING_FEE = "shipping_fee";
    case SALES_TAX = "sales_tax";
    case STORE_CREDIT_SURCHARGE = "store_credit_surcharge";
    
}