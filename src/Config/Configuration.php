<?php
namespace AddonPaymentsSDK\Config;

use AddonPaymentsSDK\Config\Parameters\Parameters;
use AddonPaymentsSDK\Config\Parameters\ParametersInterface;
class Configuration
{
    private Credentials $credentials;
    private ParametersInterface $parameters;
    private null|string $baseUrl = null;
    private array $config = [
        'otherConfigurations' => []
    ];


    public function __construct(Credentials $credentials, ParametersInterface $parameters )
    {
        $parameters->validate();
        $this->credentials = $credentials;


        $this->parameters = $parameters;

        $this->setConfig();
    }




    /**
     * Retrieves the Credentials instance.
     * 
     * @return Credentials The credentials object.
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * Retrieves the Parameters instance.
     * 
     * @return ParametersInterface The parameters interface.
     */
    public function getParameters(): ParametersInterface
    {
        return $this->parameters;
    }

    /**
     * Retrieves the base URL.
     * 
     * @return null|string The base URL or null if not set.
     */
    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    /**
     * Sets the Credentials instance.
     * 
     * @param Credentials $credentials The credentials object.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setCredentials(Credentials $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }


    /**
     * Sets the base URL for payment processing.
     * 
     * @param string $baseUrl The base URL.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        $this->config['baseUrl'] = $baseUrl;  // add this to the config array too
        return $this;
    }

    /**
     * Sets the Parameters instance.
     * 
     * @param Parameters $parameters The parameters object.
     * @return self Returns the instance of the class for method chaining.
     */
    public function setParameters(Parameters $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }



    /**
     * Sets up the configuration array.
     * 
     * Combines credentials and other parameters into a single configuration array.
     */
    public function setConfig(): void
    {

        $parameters = $this->getParameters()->getOtherConfigurations();
        $credentials = $this->getCredentials()->getConfig();
        foreach ($credentials as $key => $parameter) {
            $this->config[$key] = $parameter;
        }

        foreach ($parameters as $key => $parameter) {
            $this->config['otherConfigurations'][$key] = $parameter;
        }
    }

    /**
     * Retrieves the production mode status.
     * 
     * @return bool True if in production mode, false otherwise.
     */
    public function getProduction(): ?bool
    {
        return $this->credentials->getEnvironment();
    }

    /**
     * Retrieves the entire configuration array.
     * 
     * @return array The configuration settings.
     */

    public function getConfig(): array
    {
        return $this->config;
    }

}
