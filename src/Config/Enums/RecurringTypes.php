<?php
namespace AddonPaymentsSDK\Config\Enums;

enum RecurringTypes : string{
   
    case NEW_COF = 'newCof'; 
    
    case COF = 'cof'; 
    case NEW_SUBSCRIPTION = 'newSubscription'; 
    case SUBSCRIPTION = 'subscription';
    case NEW_INSTALLMENT = 'newInstallment'; 
    case INSTALLMENT = 'installment';
   
    


    

  
}