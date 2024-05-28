<?php

namespace AddonPaymentsSDK\QuixNotificationModel\Utils;

class PaymentDetails
{
    private ?string $cardHolderName;
    private ?string $cardNumber;
    private ?int $cardNumberToken;
    private ?string $cardType;
    private ?int $expDate;
    private ?ExtraDetails $extraDetails;
    private ?string $issuerBank;
    private ?string $issuerCountry;

    public function __construct(?object $paymentDetails)
    {

        $this->setCardHolderName(isset($paymentDetails->cardHolderName) ? $paymentDetails->cardHolderName : null);
        $this->setCardNumber(isset($paymentDetails->cardNumber) ? $paymentDetails->cardNumber : null);
        $this->setCardNumberToken(isset($paymentDetails->cardNumberToken) ? $paymentDetails->cardNumberToken : null);
        $this->setCardType(isset($paymentDetails->cardType) ? $paymentDetails->cardType : null);
        $this->setExpDate(isset($paymentDetails->expDate) ? $paymentDetails->expDate : null);
        $this->setIssuerBank(isset($paymentDetails->issuerBank) ? $paymentDetails->issuerBank : null);
        $this->setIssuerCountry(isset($paymentDetails->issuerCountry) ? $paymentDetails->issuerCountry : null);
        // Process extraDetails
        $this->setExtraDetails(isset($paymentDetails->extraDetails) ? $paymentDetails->extraDetails : null);
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

    private function setCardNumberToken(?int $cardNumberToken): void
    {
        $this->cardNumberToken = $cardNumberToken;
    }

    private function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    private function setExpDate(?int $expDate): void
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

    private function setExtraDetails(?object $extraDetails): void
    {
        
        $this->extraDetails = new ExtraDetails($extraDetails);
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
     * @return int|null The card number token or null if not found.
     */
    public function getCardNumberToken(): ?int
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
     * @return int|null The expiration date or null if not found.
     */
    public function getExpDate(): ?int
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
     * @return ExtraDetails|null The extra details as an associative array or null if not found.
     */
    public function getExtraDetails(): ?ExtraDetails
    {
        return $this->extraDetails;
    }
}
