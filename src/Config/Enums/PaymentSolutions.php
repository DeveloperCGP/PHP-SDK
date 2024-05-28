<?php
namespace AddonPaymentsSDK\Config\Enums;

enum PaymentSolutions : string{
   
    case CREDITCARDS = 'creditcards'; 
    case BIZUM = 'bizum'; 
    case PAYPAL = 'paypal'; 
    case QUIX = 'quix'; 
    case CAIXAPUCPUCE = 'caixapucpuce';
   

   
}