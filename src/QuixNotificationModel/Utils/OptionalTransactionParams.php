<?php

namespace AddonPaymentsSDK\QuixNotificationModel\Utils;


class OptionalTransactionParams
{
    private ?string $chEmail;


    public function __construct(mixed $optionalTransactionParams)
    {
       $this->setChEmail($optionalTransactionParams->chEmail ?? null);
       
    }

    private function setChEmail(?string $chEmail): void{
        $this->chEmail = $chEmail;
    }


     /**
     * Get the chEmail value.
     *
     * @return string|null The chEmail value or null if not found.
     */
    public function getChEmail() : ?string {
        return $this->chEmail;
    }

 
}