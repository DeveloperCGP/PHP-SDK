<?php
namespace AddonPaymentsSDK\Config\Enums;

enum Environment : int {

    case STAGING = 0;
    case PRODUCTION = 1;

    public function isProduction(): bool {
        return $this === self::PRODUCTION;
    }
}
