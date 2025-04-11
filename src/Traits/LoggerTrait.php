<?php

namespace AddonPaymentsSDK\Traits;

trait LoggerTrait
{
    private function logMessage(string $message, bool|string $responseRaw, mixed $headers = null, mixed $data = null, string $intType = null, string $txnType = null, string $txnId = null, array $dataNotEncrypted = null): void
    {

        if ($intType === null && $txnId === null && $data === null && $headers === null) {
            $this->logError($message);
        } else {
            $logDir = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/../logs' : '/var/www/logs';
            $logFile = $logDir . "/" . date('ymd_His') . "__" . $intType . "_" . $txnType . "_" . $txnId . "_Log.txt";

            // Check if the logs directory exists, and create it if it doesn't
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true); // Creates the directory with recursive flag
            }

            // Check if the log file exists, and create it if it doesn't
            if (!file_exists($logFile)) {
                touch($logFile); // Creates an empty log file
            }

            $message .= "\nResponse:\n" . $this->formatResponse($responseRaw);
            $message .= "\nHeaders:\n" . $this->maskData($headers);
            $message .= "\nBody:\n" . $this->maskData($data, $dataNotEncrypted);
            error_log($message . "\n", 3, $logFile);
        }
    }

    private function logError(string $message): void
    {
        $logDir = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/../logs' : '/var/www/logs';
        ;
        $logFile = $logDir . '/sdk.log';

        // Check if the logs directory exists, and create it if it doesn't
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); // Creates the directory with recursive flag
        }

        // Check if the log file exists, and create it if it doesn't
        if (!file_exists($logFile)) {
            touch($logFile); // Creates an empty log file
        }

        // Log the error to the default PHP error log
        error_log("[" . date('ymd_His') . "] ERROR: " . $message . "\n", 3, $logFile);
    }

    private function maskData(mixed $data, mixed $dataNotEnc = null): mixed
    {

        $bodyData = null;
        if (is_array($data)) {
            if (isset($data['merchantKey']))
                $data['merchantKey'] = $this->mask($data['merchantKey']);
            if (isset($data['merchantId']))
                $data['merchantId'] = $this->mask($data['merchantId']);
            if (isset($data['productId']))
                $data['productId'] = $this->mask($data['productId']);
            if ($dataNotEnc != null) {
                if (isset($dataNotEnc['merchantKey']))
                    $dataNotEnc['merchantKey'] = $this->mask($dataNotEnc['merchantKey']);
                if (isset($dataNotEnc['merchantId']))
                    $dataNotEnc['merchantId'] = $this->mask($dataNotEnc['merchantId']);
                if (isset($dataNotEnc['productId']))
                    $dataNotEnc['productId'] = $this->mask($dataNotEnc['productId']);
                if (isset($dataNotEnc['customerEmail']))
                    $dataNotEnc['customerEmail'] = $this->mask($dataNotEnc['customerEmail']);
                if (isset($dataNotEnc['cvnNumber']))
                    $dataNotEnc['cvnNumber'] = $this->mask($dataNotEnc['cvnNumber']);
                if (isset($dataNotEnc['expDate']))
                    $dataNotEnc['expDate'] = $this->mask($dataNotEnc['expDate']);
                if (isset($dataNotEnc['cardNumber']))
                    $dataNotEnc['cardNumber'] = $this->mask($dataNotEnc['cardNumber']);
                $bodyData = print_r($data, true) . "\nData Sent: \n" . print_r($dataNotEnc, true);
            } else {
                $bodyData = print_r($data, true);
            }
        } else {
            $decoded = json_decode($data, false);
            if (isset($decoded->merchantKey))
                $decoded->merchantKey = $this->mask($decoded->merchantKey);
            if (isset($decoded->merchantId))
                $decoded->merchantId = $this->mask($decoded->merchantId);
            if (isset($decoded->productId))
                $decoded->productId = $this->mask($decoded->productId);
            $bodyData = json_encode($decoded, JSON_PRETTY_PRINT);
        }

        return $bodyData;
    }

    private function mask(string|int $value): string
    {
        $valueStr = (string) $value; // Cast to string to handle both string and integer inputs
        if (strlen($valueStr) <= 4) {
            return str_repeat('*', strlen($valueStr));
        }
        return str_repeat('*', strlen($valueStr) - 4) . substr($valueStr, -4);
    }

    /**
     * @psalm-suppress InvalidScalarArgument
     */
    private function formatResponse(string|bool $responseRaw): string|bool
    {

        $decodedJson = json_decode((string) $responseRaw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decodedJson, JSON_PRETTY_PRINT);
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string((string) $responseRaw);
        if ($xml !== false) {
            $dom = new \DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($responseRaw);
            return $dom->saveXML();
        }

        // If it's not JSON or XML, return the raw response
        return $responseRaw;
    }
}
