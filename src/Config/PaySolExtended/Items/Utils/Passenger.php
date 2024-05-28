<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items\Utils;

class Passenger
{
    private ?array $passanger = null;

    /**
     * Set the first name of the passenger.
     * 
     * @param string $firstName The first name of the passenger.
     * @return static Returns the instance of the class for method chaining.
     */
    public function setFirstName(string $firstName): static
    {
        $this->passanger['first_name'] = $firstName;
        return $this;
    }

    /**
     * Set the last name of the passenger.
     * 
     * @param string $lastName The last name of the passenger.
     * @return static Returns the instance of the class for method chaining.
     */
    public function setLastName(string $lastName): static
    {
        $this->passanger['last_name'] = $lastName;
        return $this;
    }

    /**
     * Get the passenger data as an array.
     * 
     * @return array|null Returns the passenger data as an array or null if not set.
     */
    public function getPassenger(): ?array
    {

        return $this->passanger;
    }

    /**
     * Validate the passenger data.
     * 
     * @return void
     * @throws \InvalidArgumentException If first name or last name is missing.
     */
    public function validate()
    {
        if (!isset($this->passanger['first_name'])) {
            throw new \InvalidArgumentException("First name is required.");
        }
        if (!isset($this->passanger['last_name'])) {
            throw new \InvalidArgumentException("Last name code is required.");
        }
    }


}
