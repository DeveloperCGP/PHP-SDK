<?php

namespace AddonPaymentsSDK\Config\PaySolExtended;

use AddonPaymentsSDK\Config\Enums\CurrencyCodes;
use AddonPaymentsSDK\Config\Enums\LanguageCodes;
use AddonPaymentsSDK\Config\PaySolExtended\Utils\Billing;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;
use AddonPaymentsSDK\Requests\Utils\Exceptions\MissingFieldException;

abstract class BaseTransaction
{
    use LoggerTrait;
    protected array $data = [];

    /**
     * Sets the product information for the transaction.
     * 
     * @param string $product The product identifier.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setProduct(string $product): self
    {
        $this->data['product'] = $product;
        return $this;
    }

    /**
     * Set the URL for confirmation cart data.
     * 
     * @param string $confirmationCartDataUrl The URL for confirmation cart data.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setConfirmationCartData(string $confirmationCartDataUrl): self
    {
        $this->data['confirmation_cart_data']['url'] = rawurlencode($confirmationCartDataUrl);
        return $this;
    }

    public function setDisableFormEdition (bool $value): self 
    {
        $this->data['disableFormEdition'] = $value;
        return $this;
    }

    /**
     * Sets the customer's user agent information.
     * 
     * @param string $userAgent The user agent of the customer.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCustomerUserAgent(string $userAgent): self
    {
        if (strlen($userAgent) > 256) {
            throw new InvalidFieldException("User agent must be alphanumeric and no more than 256 characters long.");
        }
        $this->data['customer']['userAgent'] = $userAgent;
        return $this;
    }

    /**
     * Sets the title of the customer.
     * 
     * @param string $title The title of the customer (e.g., Mr., Ms., Dr.).
     * @return self Returns the instance of the class for method chaining.
     */

    public function setCustomerTitle(string $title): self
    {
        $this->data['customer']['title'] = $title;
        return $this;
    }

    /**
     * Sets the document expiration date for the customer.
     * 
     * @param string $documentExpirationDate The expiration date of the customer's identification document.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCustomerDocumentExpirationDate(string $documentExpirationDate): self
    {
        if (!$this->isValidDateTime($documentExpirationDate, 'Y-m-d\TH:i:s')) {
            throw new InvalidFieldException("Document expiration date must be in the format YYYY-MM-DDTHH:mm:ss.");
        }
        $this->data['customer']['document_expiration_date'] = $documentExpirationDate;
        return $this;
    }

    /**
     * Sets the customer's login status.
     * 
     * @param bool $loggedIn The login status of the customer (e.g., 'true' or 'false').
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCustomerLoggedIn(bool $loggedIn): self
    {
        $this->data['customer']['logged_in'] = $loggedIn;
        return $this;
    }

    /**
     * Sets the customer's locale.
     * 
     * @param LanguageCodes $locale The login status of the customer (e.g., 'es' or 'en').
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCustomerLocale(LanguageCodes $locale): self
    {
        $this->data['customer']['locale'] = $locale;
        return $this;
    }

    /**
     * Sets the shipping method for the transaction.
     * 
     * @param string $method The shipping method (e.g., 'Standard', 'Express').
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingMethod(string $method): self
    {
        $this->data['shipping']['method'] = $method;
        return $this;
    }

    /**
     * Sets the shipping name, typically the name of the shipping service.
     * 
     * @param string $name The name of the shipping service.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingName(string $name): self
    {
        $this->data['shipping']['name'] = $name;
        return $this;
    }

    /**
     * Sets the first name of the shipping recipient.
     * 
     * @param string $firstName The first name of the recipient.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingFirstName(string $firstName): self
    {
        $this->data['shipping']['first_name'] = $firstName;
        return $this;
    }

    /**
     * Sets the last name of the shipping recipient.
     * 
     * @param string $lastName The last name of the recipient.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingLastName(string $lastName): self
    {
        $this->data['shipping']['last_name'] = $lastName;
        return $this;
    }

    /**
     * Sets the company name associated with the shipping address.
     * 
     * @param string $company The company name for the shipping address.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingCompany(string $company): self
    {
        $this->data['shipping']['company'] = $company;
        return $this;
    }

    /**
     * Sets the phone number for shipping contact.
     * 
     * @param string $phoneNumber The phone number for shipping communications.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingPhoneNumber(string $phoneNumber): self
    {
        $this->data['shipping']['phone_number'] = $phoneNumber;
        return $this;
    }

    /**
     * Sets the email address for shipping notifications.
     * 
     * @param string $email The email address for shipping updates.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setShippingEmail(string $email): self
    {
        $this->data['shipping']['email'] = $email;
        return $this;
    }

    /**
     * Sets the billing information using the Billing object.
     * 
     * @param Billing $billing The Billing object containing billing information.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBilling(Billing $billing): self
    {
        $this->data['billing'] = $billing->getBilling();
        return $this;
    }

    /**
     * Sets the currency for the cart.
     * 
     * @param CurrencyCodes $currency The currency code.
     * @return self Returns the instance of the class for method chaining.
     * @throws InvalidFieldException If the currency code is invalid.
     */
    public function setCartCurrency(CurrencyCodes $currency): self
    {
    
        $this->data['cart']['currency'] = $currency->value;
        return $this;
    }

    /**
     * Sets the total price of the cart, including tax.
     * 
     * @param float $totalPrice The total price of all items in the cart including tax.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCartTotalPriceWithTax(float $totalPrice): self
    {
        $this->data['cart']['total_price_with_tax'] = $totalPrice;
        return $this;
    }

    /**
     * Sets a reference number or identifier for the cart.
     * 
     * @param int $reference A unique reference number for the cart.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCartReference(int $reference): self
    {
        $this->data['cart']['reference'] = $reference;
        return $this;
    }






    // Getters
    /**
     * Retrieves the total price with tax of the items in the cart.
     * 
     * @return int The total price with tax.
     */
    public function getCartTotalPriceWithTax(): float
    {
        return $this->data['cart']['total_price_with_tax'];
    }

    /**
     * Get the cart items data as an array.
     * 
     * @return array Returns an array containing cart items data.
     */
    public function getItems(): array
    {
        return $this->data['cart'];
    }

    /**
     * Get all transaction data as an array.
     * 
     * @return array Returns an array containing all transaction data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Calculates the total price of all items in the cart.
     * 
     * @return float The total price of items.
     */
    public function calculateTotalItemPrice(): float
    {
        $totalPrice = 0.0;
        // Assuming you have a way to access the items in paysolExtendedData

        foreach ($this->data['cart']['items'] as $item) {
            $totalPrice += $item['total_price_with_tax'];
        }

        return $totalPrice;

    }

    private function isValidDateTime(string $date, string $format) : bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

   

    // This method can be used to validate the data before processing the transaction
    /**
     * @return void
     */
    public function validate()
    {
        $requiredKeys = [
            'product' => 'Product',
            'billing' => [
                'label' => 'Billing',
                'keys' => [
                    'first_name' => 'BillingFirstName',
                    'last_name' => 'BillingLastName',
                    'address' => 'BillingAddress'
                ]
            ],
            'cart' => [
                'label' => 'Cart',
                'keys' => [
                    'currency' => 'CartCurrency'
                ]
            ]
        ];

        $missingKeys = [];

        foreach ($requiredKeys as $key => $value) {
            if (is_array($value)) {
                if (!isset($this->data[$key]) || empty($this->data[$key])) {
                    $missingKeys[] = $value['label'];
                } else {
                    foreach ($value['keys'] as $subKey => $subLabel) {
                        if (!isset($this->data[$key][$subKey]) || $this->data[$key][$subKey] == '') {
                            $missingKeys[] = $subLabel;
                        }
                    }
                }
            } else {
                if (!isset($this->data[$key]) || $this->data[$key] == '') {
                    $missingKeys[] = $value;
                }
            }
        }

        if (!empty($missingKeys)) {
            $this->logError('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
            throw new MissingFieldException('Mandatory parameters are missing. Please ensure you provide:  ' . implode(', ', $missingKeys) . '.');
        }
        // Check if there are any items in the cart
        if (count($this->data['cart']['items'] ?? []) === 0) {
            $this->logError("The cart should have at least one item.");
            throw new InvalidFieldException("The cart should have at least one item.");
        }

        if (!isset($this->data['cart']['total_price_with_tax'])) {
            $this->setCartTotalPriceWithTax($this->calculateTotalItemPrice());
        }

        if (isset($this->data['cart']['total_price_with_tax']) && $this->calculateTotalItemPrice() != $this->getCartTotalPriceWithTax()) {
            $this->logError("The total amount specified in the cart does not match the sum of 'total_price_with_tax'" . $this->data['cart']['total_price_with_tax']. "for each item ". $this->calculateTotalItemPrice() . ". Please ensure the item prices correctly add up to the total cart amount.");
            throw new InvalidFieldException("The total amount specified in the cart does not match the sum of 'total_price_with_tax'" . $this->data['cart']['total_price_with_tax']. "for each item ". $this->calculateTotalItemPrice() . ". Please ensure the item prices correctly add up to the total cart amount.");
        }

        if (!isset($this->data['cart']['total_price_with_tax'])) {
            $this->logError("Cart total_price_with_tax is Required.");
            throw new MissingFieldException("Cart total_price_with_tax is Required.");
        }

    }

    // This method can be used to process the transaction
    public function process(): void
    {
        // Implement the logic to process the transaction
        // For example, send the data to an API endpoint
    }
}
