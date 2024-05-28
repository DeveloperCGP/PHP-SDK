<?php

namespace AddonPaymentsSDK\Traits;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;
trait ErrorHandlerTrait
{
    
    public function checkConfig(array $config, bool $merchantPasswordCheck): void
    {

        $requiredKeys = [
            'merchantId' => 'merchantId',
            'productId' => 'productId',
            'environment' => 'environment',
            'otherConfigurations' => 'otherConfigurations'
        ];
        if ($merchantPasswordCheck) {
            $requiredKeys['merchantPassword'] = 'merchantPassword';
        }

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || (is_null($config[$key]) || (is_string($config[$key]) && $config[$key] === ''))) {
                $missingKeys[] = $label;
            }
        }



        if (!empty($missingKeys)) {
            $this->logError('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }


    public  function checkRedirectionPaymentConfig(array $config): void
    {
        $requiredKeys = [
            'currency' => 'currency',
            'amount' => 'amount',
            'country' => 'country',
            'customerId' => 'customerId',
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'operationType' => 'operationType',
            'statusURL' => 'statusURL',
            'successURL' => 'successURL',
            'errorURL' => 'errorURL',
            'awaitingURL' => 'awaitingURL',

        ];



        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }


    public function checkQuixRedirectionPaymentConfig(array $config): void
    {
        $requiredKeys = [
            'currency' => 'currency',
            'amount' => 'amount',
            'country' => 'country',
            'customerId' => 'customerId',
            'ipAddress' => 'ipAddress',
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'statusURL' => 'statusURL',
            'successURL' => 'successURL',
            'errorURL' => 'errorURL',
            'awaitingURL' => 'awaitingURL',

        ];



        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }

    public  function checkVoidPaymentConfig(array $config): void
    {
        $requiredKeys = [
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'transactionId' => 'transactionId'
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }

    public function checkRefundPaymentConfig(array $config): void
    {
        $requiredKeys = [
            'amount' => 'amount',
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'transactionId' => 'transactionId'
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }

    public  function checkCapturePaymentConfig(array $config): void
    {
        $requiredKeys = [
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'transactionId' => 'transactionId',
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }

    public function checkH2HPaymentConfig(array $config): void
    {
        $requiredKeys = [
            'currency' => 'currency',
            'amount' => 'amount',
            'country' => 'country',
            'customerId' => 'customerId',
            'merchantTransactionId' => 'merchantTransactionId',
            'paymentSolution' => 'paymentSolution',
            'operationType' => 'operationType',
            'statusURL' => 'statusURL',
            'successURL' => 'successURL',
            'errorURL' => 'errorURL',
            'awaitingURL' => 'awaitingURL',
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        // Check for card data entry methods
        if (!isset($config['cardNumberToken'])) {
            $cardDetailsKeys = [
                'cardNumber' => 'cardNumber',
                'expDate' => 'expDate',
                'cvnNumber' => 'cvnNumber',
                'chName' => 'chName',
            ];



            foreach ($cardDetailsKeys as $key => $label) {
                if (!isset($config[$key]) || $config[$key] == '') {
                    $missingKeys[] = $label;
                }
            }


        } elseif (isset($config['cardNumber']) || isset($config['cardType']) || isset($config['expDate']) || isset($config['cvnNumber'])) {
            $this->logError('You provided partial card details along with a cardNumberToken. Please provide either a full set of card details or only the cardNumberToken.');
            throw new MissingFieldException('You provided partial card details along with a cardNumberToken. Please provide either a full set of card details or only the cardNumberToken.');
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
    }

    public function checkJsAuthRequestConfig(array $config): void
    {
        $requiredKeys = [
            'merchantId' => 'merchantId',
            'merchantKey' => 'merchantKey',
            'productId' => 'productId',

        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }


        $requiredOtherConfigKeys = [
            'currency' => 'currency',
            'country' => 'country',
            'customerId' => 'customerId',
            'operationType' => 'operationType'
        ];

        if (!isset($config['otherConfigurations'])) {
            $this->logError('Missing otherConfigurations. Please ensure you provide valid configurations.');
            throw new MissingFieldException('Missing otherConfigurations. Please ensure you provide valid configurations.');
        }

        $missingParamatersKeys = [];

        foreach ($requiredOtherConfigKeys as $key => $label) {
            if (!isset($config['otherConfigurations'][$key]) || $config['otherConfigurations'][$key] == '') {
                $missingParamatersKeys[] = $label;
            }
        }

        if (!empty($missingParamatersKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
        }




    }


    public function checkQuixJsAuthRequestConfig(array $config): void
    {
        $requiredKeys = [
            'merchantId' => 'merchantId',
            'merchantKey' => 'merchantKey',
            'productId' => 'productId',

        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }


        $requiredOtherConfigKeys = [
            'currency' => 'currency',
            'country' => 'country',
            'customerId' => 'customerId',
        ];

        if (!isset($config['otherConfigurations'])) {
            $this->logError('Missing otherConfigurations. Please ensure you provide valid configurations.');
            throw new MissingFieldException('Missing otherConfigurations. Please ensure you provide valid configurations.');
        }

        $missingParamatersKeys = [];

        foreach ($requiredOtherConfigKeys as $key => $label) {
            if (!isset($config['otherConfigurations'][$key]) || $config['otherConfigurations'][$key] == '') {
                $missingParamatersKeys[] = $label;
            }
        }

        if (!empty($missingParamatersKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
        }




    }

    public function checkJsChargeRequestConfig(array $config, bool $quix = false): void
    {
        $requiredKeys = [
            'merchantId' => 'merchantId',
            'productId' => 'productId',
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }

        $requiredOtherConfigKeys = [
            'prepayToken' => 'prepayToken',
            'currency' => 'currency',
            'amount' => 'amount',
            'country' => 'country',
            'customerId' => 'customerId',
            'apiVersion' => 'apiVersion',
            
            'operationType' => 'operationType',
            'merchantTransactionId' => 'merchantTransactionId',
            'statusURL' => 'statusURL',
            'successURL' => 'successURL',
            'errorURL' => 'errorURL',
            'awaitingURL' => 'awaitingURL',
        ];

        if (!isset($config['otherConfigurations'])) {
            $this->logError('Missing otherConfigurations. Please ensure you provide valid configurations.');
            throw new MissingFieldException('Missing otherConfigurations. Please ensure you provide valid configurations.');
        }

        $missingParamatersKeys = [];

        foreach ($requiredOtherConfigKeys as $key => $label) {
            if (!isset($config['otherConfigurations'][$key]) || $config['otherConfigurations'][$key] == '') {
                $missingParamatersKeys[] = $label;
            }
        }

        if (!empty($missingParamatersKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
        }
    }


    public function checkQuixJsChargeRequestConfig(array $config): void
    {
        $requiredKeys = [
            'merchantId' => 'merchantId',
            'productId' => 'productId',
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $label) {
            if (!isset($config[$key]) || $config[$key] == '') {
                $missingKeys[] = $label;
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory credentials are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }

        $requiredOtherConfigKeys = [
            'prepayToken' => 'prepayToken',
            'currency' => 'currency',
            'amount' => 'amount',
            'merchantTransactionId' => 'merchantTransactionId',
            'country' => 'country',
            'customerId' => 'customerId',
            'successURL' => 'successURL',
            'ipAddress' => 'ipAddress',
            'errorURL' => 'errorURL',
            'cancelURL' => 'cancelURL',
            'statusURL' => 'statusURL',
            'awaitingURL' => 'awaitingURL',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'customerEmail' => 'customerEmail',
            'paysolExtendedData' => 'paysolExtendedData'

        ];

        $missingParamatersKeys = [];

        foreach ($requiredOtherConfigKeys as $key => $label) {
            if (!isset($config['otherConfigurations'][$key]) || $config['otherConfigurations'][$key] == '') {
                $missingParamatersKeys[] = $label;
            }
        }

        if (!empty($missingParamatersKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingParamatersKeys) . '.');
        }
    }
}