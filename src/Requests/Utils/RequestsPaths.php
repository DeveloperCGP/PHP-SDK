<?php 

namespace AddonPaymentsSDK\Requests\Utils;

class RequestsPaths {
    const HOSTED_STG = 'https://checkout-stg.addonpayments.com/EPGCheckout/rest/online/tokenize';
    const HOSTED_PROD = 'https://checkout.addonpayments.com/EPGCheckout/rest/online/tokenize';

    const H2H_STG = 'https://checkout-stg.addonpayments.com/EPGCheckout/rest/online/pay';
    const H2H_PROD = 'https://checkout.addonpayments.com/EPGCheckout/rest/online/pay';
   
    const CAPTURE_STG = 'https://checkout-stg.addonpayments.com/EPGCheckout/rest/online/capture';
    const CAPTURE_PROD = 'https://checkout.addonpayments.com/EPGCheckout/rest/online/capture';

    const REBATE_STG = 'https://checkout-stg.addonpayments.com/EPGCheckout/rest/online/rebate';
    const REBATE_PROD = 'https://checkout.addonpayments.com/EPGCheckout/rest/online/rebate';

    const VOID_STG = 'https://checkout-stg.addonpayments.com/EPGCheckout/rest/online/void';
    const VOID_PROD = 'https://checkout.addonpayments.com/EPGCheckout/rest/online/void';

    const JS_AUTH_STG = 'https://epgjs-mep-stg.addonpayments.com/auth';
    const JS_AUTH_PROD = 'https://epgjs-mep.addonpayments.com/auth';

    const JS_CHARGE_STG = 'https://epgjs-mep-stg.addonpayments.com/charge/v2';
    const JS_CHARGE_PROD = 'https://epgjs-mep.addonpayments.com/charge/v2';

}