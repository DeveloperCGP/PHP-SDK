<?php

namespace AddonPaymentsSDK\NotificationModel\Operations;

use AddonPaymentsSDK\NotificationModel\Utils\ExtraDetailsProcessor;

class PaymentDetails
{
    private ?string $cardHolderName;
    private ?string $cardNumber;
    private ?string $cardNumberToken;
    private ?string $cardType;
    private ?string $expDate;
    private ?array $extraDetails = [];
    private ?string $issuerBank;
    private ?string $issuerCountry;

    public function __construct(?object $paymentDetails)
    {
        $this->setCardHolderName(isset($paymentDetails->cardHolderName) ? trim($paymentDetails->cardHolderName) : null);
        $this->setCardNumber(isset($paymentDetails->cardNumber) ? trim($paymentDetails->cardNumber) : null);
        $this->setCardNumberToken(isset($paymentDetails->cardNumberToken) ? trim($paymentDetails->cardNumberToken) : null);
        $this->setCardType(isset($paymentDetails->cardType) ? trim($paymentDetails->cardType) : null);
        $this->setExpDate(isset($paymentDetails->expDate) ? trim($paymentDetails->expDate) : null);
        $this->setIssuerBank(isset($paymentDetails->issuerBank) ? trim($paymentDetails->issuerBank) : null);
        $this->setIssuerCountry(isset($paymentDetails->issuerCountry) ? trim($paymentDetails->issuerCountry) : null);

        // Process extraDetails using utility function
        if(isset($paymentDetails->extraDetails)){
            $this->extraDetails = ExtraDetailsProcessor::processExtraDetails($paymentDetails->extraDetails);
        }
    }

    // Setters

    private function setCardHolderName(?string $cardHolderName): void
    {
        $this->cardHolderName = $cardHolderName;
    }

    private function setCardNumber(?string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    private function setCardNumberToken(?string $cardNumberToken): void
    {
        $this->cardNumberToken = $cardNumberToken;
    }

    private function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    private function setExpDate(?string $expDate): void
    {
        $this->expDate = $expDate;
    }

    private function setIssuerBank(?string $issuerBank): void
    {
        $this->issuerBank = $issuerBank;
    }

    private function setIssuerCountry(?string $issuerCountry): void
    {
        $this->issuerCountry = $issuerCountry;
    }

    // Getters

    /**
     * Get the cardholder's name associated with the payment details.
     *
     * @return string|null The cardholder's name or null if not found.
     */
    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    /**
     * Get the card number associated with the payment details.
     *
     * @return string|null The card number or null if not found.
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * Get the card number token associated with the payment details.
     *
     * @return string|null The card number token or null if not found.
     */
    public function getCardNumberToken(): ?string
    {
        return $this->cardNumberToken;
    }

    /**
     * Get the card type associated with the payment details.
     *
     * @return string|null The card type or null if not found.
     */
    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * Get the expiration date of the card associated with the payment details.
     *
     * @return string|null The expiration date or null if not found.
     */
    public function getExpDate(): ?string
    {
        return $this->expDate;
    }

    /**
     * Get the issuer bank associated with the payment details.
     *
     * @return string|null The issuer bank or null if not found.
     */
    public function getIssuerBank(): ?string
    {
        return $this->issuerBank;
    }

    /**
     * Get the issuer country associated with the payment details.
     *
     * @return string|null The issuer country or null if not found.
     */
    public function getIssuerCountry(): ?string
    {
        return $this->issuerCountry;
    }

    /**
     * Get the extra details associated with the payment details.
     *
     * @return array|null The extra details as an associative array or null if not found.
     */
    public function getExtraDetails(): ?array
    {
        return $this->extraDetails;
    }
}
