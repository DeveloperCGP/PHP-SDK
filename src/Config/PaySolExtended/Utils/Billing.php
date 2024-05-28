<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Utils;

class Billing
{
    private ?array $billing = null;

    /**
     * Set the first name for billing information.
     * 
     * @param string $firstName The first name for billing.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBillingFirstName(string $firstName): self
    {
        $this->billing['first_name'] = $firstName;
        return $this;
    }

    /**
     * Set the last name for billing information.
     * 
     * @param string $lastName The last name for billing.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBillingLastName(string $lastName): self
    {
        $this->billing['last_name'] = $lastName;
        return $this;
    }

    /**
     * Set the corporate ID number for billing information.
     * 
     * @param int $corporateIdNumber The corporate ID number for billing.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBillingCorporateIdNnumber(int $corporateIdNnumber): self
    {
        $this->billing['corporate_id_number'] = $corporateIdNnumber;
        return $this;
    }

    /**
     * Set the billing address details.
     * 
     * @param string $streetAddress The street address for billing.
     * @param int $postalCode The postal code for billing.
     * @param string $city The city for billing.
     * @param string $country The country for billing.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBillingAddress(string $streetAddress, int $postalCode, string $city, string $country): self
    {
        $this->billing['address'] = [
            'street_address' => $streetAddress,
            'postal_code' => $postalCode,
            'city' => $city,
            'country' => $country
        ];
        return $this;
    }

    public function getBilling(): mixed
    {
        return $this->billing;
    }

}