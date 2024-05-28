<?php

namespace AddonPaymentsSDK;


use AddonPaymentsSDK\Requests\CreateCaptureRequest;
use AddonPaymentsSDK\Traits\ErrorHandlerTrait;
use AddonPaymentsSDK\Traits\LoggerTrait;
use AddonPaymentsSDK\Config\Configuration;
use AddonPaymentsSDK\Requests\CreateRedirectionRequest;
use AddonPaymentsSDK\Requests\CreateH2HRequest;
use AddonPaymentsSDK\Requests\CreateVoidRequest;
use AddonPaymentsSDK\Requests\CreateRefundRequest;
use AddonPaymentsSDK\Requests\CreateAuthTokenRequest;
use AddonPaymentsSDK\Requests\CreateChargeRequest;
use AddonPaymentsSDK\Requests\CreateQuixChargeRequest;

/**
 * Main class for the AddonPayments SDK.
 * This class initializes and manages the various payment requests and configurations.
 */
class AddonPaymentsSDK
{
    use ErrorHandlerTrait;
    use LoggerTrait;

    private ?int $merchantId;
    private ?string $merchantPassword;
    private ?string $merchantKey = null;

    private ?int $productId = null;
    private array $otherConfigurations;
    private ?string $baseUrl;
    private ?bool $production = null;
    private CreateRedirectionRequest $createRequest;
    private CreateH2HRequest $createH2HRequest;
    private CreateVoidRequest $createVoidRequest;

    private CreateRefundRequest $createRefundRequest;
    private CreateCaptureRequest $createCaptureRequest;


    private CreateAuthTokenRequest $jsAuthTokenRequest;
    private CreateChargeRequest $jsChargeRequest;
    private CreateQuixChargeRequest $jsQuixChargeRequest;



    private Configuration $configuration;


    public function __construct(Configuration $config)
    {

        $this->configuration = $config;
        $this->merchantId = $config->getCredentials()->getMerchantId(); 
        $this->merchantPassword = $config->getCredentials()->getMerchantPassword(); 
        $this->otherConfigurations = $config->getConfig()['otherConfigurations']; 
        $this->baseUrl = $config->getBaseUrl(); // Same assumption as above
        $this->production = $config->getProduction();

        $this->configuration = $config;
        // Constructor logic, if needed.
        $this->createRequest = new CreateRedirectionRequest();
        $this->createH2HRequest = new CreateH2HRequest();
        $this->createVoidRequest = new CreateVoidRequest();
        $this->createRefundRequest = new CreateRefundRequest();
        $this->createCaptureRequest = new CreateCaptureRequest();
        $this->jsAuthTokenRequest = new CreateAuthTokenRequest();
        $this->jsChargeRequest = new CreateChargeRequest();
        $this->jsQuixChargeRequest = new CreateQuixChargeRequest();
    }


    /**
     * Sets the configuration for the SDK.
     *
     * @param Configuration $config The new configuration to be set.
     */
    private function setConfiguration(Configuration $config): void
    {
        $this->updateConfiguration($config);
        $this->configuration = $config;
    }


    /**
     * Updates the SDK configuration based on a given configuration object.
     * @psalm-assert !null $this->merchantPassword
     * @psalm-assert !null $this->merchantId
     * @param Configuration $configClass The configuration object used to update settings.
     */
    private function updateConfiguration(Configuration $configClass, bool $merchantPasswordCheck = true): void
    {
        $config = $configClass->getConfig();
      
        $this->checkConfig($config , $merchantPasswordCheck);  // Directly call the method

        $this->merchantId = $config['merchantId'];
        $this->merchantPassword = isset($config['merchantPassword']) ? $config['merchantPassword'] : null;
        if (isset($config['merchantKey']))
            $this->merchantKey = $config['merchantKey'];
        if (isset($config['productId']))
            $this->productId = $config['productId'];

        $this->otherConfigurations = $config['otherConfigurations'];
        $this->baseUrl = isset($config['baseUrl']) ? $config['baseUrl'] : null;
        $this->production = $configClass->getProduction();


    }

    /**
     * Processes a redirection payment request.
     *
     * @return CreateRedirectionRequest The request object after processing.
     */
    public function sendRedirectionPaymentRequest(): CreateRedirectionRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);
        
        if (isset($this->productId))
        $this->otherConfigurations['productId'] = $this->productId;

        $this->checkRedirectionPaymentConfig($this->otherConfigurations);
        // Directly call the method



        $this->createRequest->initRedirectionPaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        $this->createRequest->encryption();
        $this->createRequest->sendRequest();

       

        return $this->createRequest;
    }


    /**
     * Processes a redirection payment request.
     *
     * @return CreateRedirectionRequest The request object after processing.
     */
    public function sendQuixRedirectionPaymentRequest(): CreateRedirectionRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);
       

        $this->checkQuixRedirectionPaymentConfig($this->otherConfigurations);
        // Directly call the method

        if (isset($this->productId))
            $this->otherConfigurations['productId'] = $this->productId;

        $this->createRequest->initRedirectionPaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        
        $this->createRequest->encryption();
        $this->createRequest->sendRequest();

     

        return $this->createRequest;
    }

    /**
     * Processes a host-to-host (H2H) payment request.
     *
     * @return CreateH2HRequest The request object after processing.
     */
    public function sendH2HPaymentRequest(): CreateH2HRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);

        if (isset($this->merchantKey))
            $this->otherConfigurations['merchantKey'] = $this->merchantKey;
        if (isset($this->productId))
            $this->otherConfigurations['productId'] = $this->productId;

     

        $this->checkH2HPaymentConfig($this->otherConfigurations);  // Directly call the method

        $this->createH2HRequest->initH2HPaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        $this->createH2HRequest->encryption();
        $this->createH2HRequest->sendRequest();

      

        return $this->createH2HRequest;
    }

    /**
     * Processes a capture payment request.
     *
     * @return CreateCaptureRequest The request object after processing.
     */
    public function sendCapturePaymentRequest(): CreateCaptureRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);

        if (isset($this->merchantKey))
            $this->otherConfigurations['merchantKey'] = $this->merchantKey;
        if (isset($this->productId))
            $this->otherConfigurations['productId'] = $this->productId;
      

        $this->checkCapturePaymentConfig($this->otherConfigurations);  // Directly call the method

        $this->createCaptureRequest->initCapturePaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        $this->createCaptureRequest->encryption();
        $this->createCaptureRequest->sendRequest();


        return $this->createCaptureRequest;
    }

    /**
     * Processes a void payment request.
     *
     * @return CreateVoidRequest The request object after processing.
     */
    public function sendVoidPaymentRequest(): CreateVoidRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);

        if (isset($this->merchantKey))
            $this->otherConfigurations['merchantKey'] = $this->merchantKey;
        if (isset($this->productId))
            $this->otherConfigurations['productId'] = $this->productId;

        $this->checkVoidPaymentConfig($this->otherConfigurations);  // Directly call the method

        $this->createVoidRequest->initVoidPaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        $this->createVoidRequest->encryption();
        $this->createVoidRequest->sendRequest();


        return $this->createVoidRequest;
    }

    /**
     * Processes a refund payment request.
     *
     * @return CreateRefundRequest The request object after processing.
     */
    public function sendRefundPaymentRequest(): CreateRefundRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration);

        if (isset($this->merchantKey))
            $this->otherConfigurations['merchantKey'] = $this->merchantKey;
        if (isset($this->productId))
            $this->otherConfigurations['productId'] = $this->productId;

        $this->checkRefundPaymentConfig($this->otherConfigurations);  // Directly call the method

        $this->createRefundRequest->initRefundPaymentRequest(
            $this->merchantId,
            $this->merchantPassword,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );
        $this->createRefundRequest->encryption();
        $this->createRefundRequest->sendRequest();


        return $this->createRefundRequest;
    }


    /**
     * Processes a JavaScript-based authentication request.
     *
     * @return CreateAuthTokenRequest The request object after processing.
     */
    public function sendJsAuthRequest(): CreateAuthTokenRequest
    {
        $this->configuration->setConfig();

        $this->updateConfiguration($this->configuration, false);

        /** @psalm-suppress PossiblyNullArgument */

        $this->checkJsAuthRequestConfig([
            'merchantId' => $this->merchantId,
            'merchantKey' => $this->merchantKey,
            'productId' => $this->productId,
            'otherConfigurations' => $this->otherConfigurations
        ]);  // Directly call the method

        /** @psalm-suppress PossiblyNullArgument */
        $this->jsAuthTokenRequest->initAuthTokentRequest(
            $this->merchantId,
            $this->productId,
            $this->merchantKey,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );

        $this->jsAuthTokenRequest->sendRequest();

        return $this->jsAuthTokenRequest;
    }


    /**
     * Processes a JavaScript-based authentication request.
     *
     * @return CreateAuthTokenRequest The request object after processing.
     */
    public function sendQuixJsAuthRequest(): CreateAuthTokenRequest
    {
        $this->configuration->setConfig();

        $this->updateConfiguration($this->configuration, false);

        /** @psalm-suppress PossiblyNullArgument */

      

        $this->checkQuixJsAuthRequestConfig([
            'merchantId' => $this->merchantId,
            'merchantPassword' => $this->merchantPassword,
            'merchantKey' => $this->merchantKey,
            'productId' => $this->productId,
            'otherConfigurations' => $this->otherConfigurations
        ]);  // Directly call the method

        /** @psalm-suppress PossiblyNullArgument */
        $this->jsAuthTokenRequest->initAuthTokentRequest(
            $this->merchantId,
            $this->productId,
            $this->merchantKey,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );

        $this->jsAuthTokenRequest->sendRequest();

        return $this->jsAuthTokenRequest;
    }

    /**
     * Processes a JavaScript-based charge request.
     *
     * @return CreateChargeRequest The request object after processing.
     */
    public function sendJsChargeRequest(): CreateChargeRequest
    {

        /** @psalm-suppress PossiblyNullArgument */
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration, false);

        $this->checkJsChargeRequestConfig([
            'merchantId' => $this->merchantId,
            'merchantPassword' => $this->merchantPassword,
            'productId' => $this->productId,
            'otherConfigurations' => $this->otherConfigurations
        ]);  // Directly call the method
        /** @psalm-suppress PossiblyNullArgument */
        $this->jsChargeRequest->initChargeRequest(
            $this->merchantId,
            $this->productId,
            $this->merchantKey,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );

        $this->jsChargeRequest->sendRequest();

        return $this->jsChargeRequest;
    }

    /**
     * Processes a Quix JavaScript-based charge request.
     *
     * @return CreateQuixChargeRequest The request object after processing.
     */
    public function sendQuixJsChargeRequest(): CreateQuixChargeRequest
    {
        $this->configuration->setConfig();
        $this->updateConfiguration($this->configuration, false);
        /** @psalm-suppress PossiblyNullArgument */

        $this->checkQuixJsChargeRequestConfig([
            'merchantId' => $this->merchantId,
            'merchantPassword' => $this->merchantPassword,
            'productId' => $this->productId,
            'otherConfigurations' => $this->otherConfigurations
        ]);  // Directly call the method
        /** @psalm-suppress PossiblyNullArgument */
        $this->jsQuixChargeRequest->initChargeRequest(
            $this->merchantId,
            $this->productId,
            $this->merchantKey,
            $this->otherConfigurations,
            $this->production,
            $this->baseUrl
        );

        $this->jsQuixChargeRequest->sendRequest();


        return $this->jsQuixChargeRequest;
    }

    /**
     * Retrieves the current configuration of the SDK.
     *
     * @return Configuration The current configuration object.
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    


}
