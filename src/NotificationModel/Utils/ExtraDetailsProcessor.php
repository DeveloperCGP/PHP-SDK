<?php

namespace AddonPaymentsSDK\NotificationModel\Utils;

class ExtraDetailsProcessor
{
    public static function processExtraDetails(?object $extraDetails): array
    {
        $processedDetails = [];
        
        if (isset($extraDetails)) {
            if (isset($extraDetails->entry)) {
                foreach ($extraDetails->entry as $entry) {
                    $key = trim((string) $entry->key);
                    $value = trim((string) $entry->value);
                    $processedDetails[$key] = $value;
                }
            } else {
                foreach ((array)$extraDetails as $key => $value) {
                    $processedDetails[$key] = trim((string) $value);
                }
            }
        }
        
        return $processedDetails;
    }
}