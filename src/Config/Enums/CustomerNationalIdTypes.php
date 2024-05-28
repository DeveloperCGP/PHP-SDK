<?php
namespace AddonPaymentsSDK\Config\Enums;

enum CustomerNationalIdTypes : string{
  
    case DNI = "DNI";
    case ID = "ID";

    case CC = "CC";
    case NIT = "NIT";
    case CE = "CE";
    case PASS = "PASS";
    case RUC = "RUC";
    
}