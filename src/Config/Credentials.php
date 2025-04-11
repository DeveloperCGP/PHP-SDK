<?php

namespace AddonPaymentsSDK\Config;

use AddonPaymentsSDK\Config\Enums\Environment;
use AddonPaymentsSDK\Requests\Utils\Exceptions\InvalidFieldException;

class Credentials {
    private array $config = [];

  /**
     * Sets the Merchant ID for payment transactions.
     * 
     * This ID is unique to each merchant and is used to identify them in transactions.
     *
     * @param string $merchantId The Merchant ID.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setMerchantId(string $merchantId): self {
        if (!preg_match('/^\d{4,7}$/', $merchantId)) {
            throw new InvalidFieldException("Merchant ID must be a whole number with 4 to 7 digits.");
        }
        $this->config['merchantId'] = $merchantId;
        return $this;
    }

  
  /**
     * Sets the Merchant Password for authentication.
     * 
     * This password is used alongside the Merchant ID for secure authentication during transactions.
     *
     * @param string $merchantPassword The Merchant Password.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setMerchantPassword(string $merchantPassword): self {
        
        $this->config['merchantPassword'] = $merchantPassword;
        return $this;
    }



    /**
     * Sets the Merchant Key for additional security.
     * 
     * This key is a part of the security credentials used for processing transactions.
     *
     * @param string $merchantKey The Merchant Key.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setMerchantKey(string $merchantKey): self {
        
        // Validate the merchant key (UUID format)
        if (!preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[1-5][a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/', $merchantKey)) {
            throw new InvalidFieldException("Merchant Key must be in UUID format.");
        }
        $this->config['merchantKey'] = $merchantKey;
        return $this;
    }

    /**
     * Retrieves the Merchant Key.
     * 
     * @return string Returns the Merchant Key.
     */
    public function getMerchantKey(): string {
        return $this->config['merchantKey'];
    }

    /**
     * Sets the Product ID for the transaction.
     * 
     * This ID is used to specify and identify the product involved in the transaction.
     *
     * @param int $productId The Product ID.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setProductId(int $productId): self {
        if (!preg_match('/^\d{6,11}$/', (string) $productId)) {
            throw new InvalidFieldException("Product ID must be a whole number with 4 to 7 digits.");
        }
        $this->config['productId'] = $productId;
        return $this;
    }

    

    /**
     * Toggles the production mode.
     * 
     * @param Environment $env True for production mode, false for testing.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setEnvironment(Environment $env) :self {
        $this->config['environment'] = $env->isProduction();
        return $this;
    }

    public function getEnvironment() : ?bool {
     
        return $this->config['environment'] ?? null;
    }

    public function getMerchantId() : ?int {
        return $this->config['merchantId'];
    }

    public function getMerchantPassword() : ?string {
        return isset($this->config['merchantPassword']) ? $this->config['merchantPassword'] : null;
    }

    

    public function getProductId() : ?int {
        return $this->config['productId'];
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    
}
