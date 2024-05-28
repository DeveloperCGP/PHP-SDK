<?php

namespace AddonPaymentsSDK\QuixNotificationModel\Utils;

class ExtraDetails
{
    private ?string $nemuruTxnId;
    private ?string $nemuruCartHash;
    private ?string $nemuruAuthToken;
    private ?string $nemuruDisableFormEdition;
    private ?string $status;

    public function __construct(?object $extraDetails = null)
    {

        $this->nemuruTxnId = null;
        $this->nemuruCartHash = null;
        $this->nemuruAuthToken = null;
        $this->nemuruDisableFormEdition = null;
        $this->status = null;
        if (!is_null($extraDetails)) {
            if (isset($extraDetails->entry) && is_array($extraDetails->entry)) {
                $this->processEntryArray($extraDetails->entry);
            } else {
                $this->processDirectProperties($extraDetails);
            }
        }
    }



    private function processEntryArray(array $entries) : void
    {
        foreach ($entries as $entry) {
            switch ($entry->key) {
                case 'nemuruTxnId':
                    $this->setNemuruTxnId($entry->value);
                    break;
                case 'nemuruCartHash':
                    $this->setNemuruCartHash($entry->value);
                    break;
                case 'nemuruAuthToken':
                    $this->setNemuruAuthToken($entry->value);
                    break;
                case 'nemuruDisableFormEdition':
                    $this->setNemuruDisableFormEdition($entry->value);
                    break;
                case 'status':
                    $this->setStatus($entry->value);
                    break;
            }
        }
    }

    private function processDirectProperties(object $details) : void
    {
        $this->setNemuruTxnId($details->nemuruTxnId ?? null);
        $this->setNemuruCartHash($details->nemuruCartHash ?? null);
        $this->setNemuruAuthToken($details->nemuruAuthToken ?? null);
        $this->setNemuruDisableFormEdition($details->nemuruDisableFormEdition ?? null);
        $this->setStatus($details->status ?? null);
    }
    public function getNemuruTxnId(): ?string
    {
        return $this->nemuruTxnId;
    }

    private function setNemuruTxnId(?string $nemuruTxnId): void
    {
        $this->nemuruTxnId = $nemuruTxnId;
    }

    public function getNemuruCartHash(): ?string
    {
        return $this->nemuruCartHash;
    }

    private function setNemuruCartHash(?string $nemuruCartHash): void
    {
        $this->nemuruCartHash = $nemuruCartHash;
    }

    public function getNemuruAuthToken(): ?string
    {
        return $this->nemuruAuthToken;
    }

    private function setNemuruAuthToken(?string $nemuruAuthToken): void
    {
        $this->nemuruAuthToken = $nemuruAuthToken;
    }

    public function getNemuruDisableFormEdition(): ?string
    {
        return $this->nemuruDisableFormEdition;
    }

    private function setNemuruDisableFormEdition(?string $nemuruDisableFormEdition): void
    {
        $this->nemuruDisableFormEdition = $nemuruDisableFormEdition;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    private function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
