<?php
namespace AddonPaymentsSDK\NotificationModel\Operations;

class MPI
{
    private mixed $acsTransID;
    private ?string $authMethod;
    private ?string $authTimestamp;
    private ?string $authenticationStatus;
    private ?string $cavv;
    private ?string $eci;
    private ?string $messageVersion;
    private ?string $threeDSSessionData;
    private ?string $threeDSv2Token;

    public function __construct(mixed $mpi)
    {
        $this->setAcsTransID(isset($mpi->acsTransID) ? $mpi->acsTransID : null);
        $this->setAuthMethod(isset($mpi->authMethod) ? $mpi->authMethod : null);
        $this->setAuthTimestamp(isset($mpi->authTimestamp) ? $mpi->authTimestamp : null);
        $this->setAuthenticationStatus(isset($mpi->authenticationStatus) ? $mpi->authenticationStatus : null);
        $this->setCavv(isset($mpi->cavv) ? $mpi->cavv : null);
        $this->setEci(isset($mpi->eci) ? $mpi->eci : null);
        $this->setMessageVersion(isset($mpi->messageVersion) ? $mpi->messageVersion : null);
        $this->setThreeDSSessionData(isset($mpi->threeDSSessionData) ? $mpi->threeDSSessionData : null);
        $this->setThreeDSv2Token(isset($mpi->threeDSv2Token) ? $mpi->threeDSv2Token : null);
    }

    // Setters
    private function setAcsTransID(mixed $acsTransID): void
    {
        $this->acsTransID = $acsTransID;
    }

    private function setAuthMethod(?string $authMethod): void
    {
        $this->authMethod = $authMethod;
    }

    private function setAuthTimestamp(?string $authTimestamp): void
    {
        $this->authTimestamp = $authTimestamp;
    }

    private function setAuthenticationStatus(?string $authenticationStatus): void
    {
        $this->authenticationStatus = $authenticationStatus;
    }

    private function setCavv(?string $cavv): void
    {
        $this->cavv = $cavv;
    }

    private function setEci(?string $eci): void
    {
        $this->eci = $eci;
    }

    private function setMessageVersion(?string $messageVersion): void
    {
        $this->messageVersion = $messageVersion;
    }

    private function setThreeDSSessionData(?string $threeDSSessionData): void
    {
        $this->threeDSSessionData = $threeDSSessionData;
    }

    private function setThreeDSv2Token(?string $threeDSv2Token): void
    {
        $this->threeDSv2Token = $threeDSv2Token;
    }

    // Getters

    /**
     * Get the ACS Trans ID associated with the MPI data.
     *
     * @return mixed The ACS Trans ID.
     */
    public function getAcsTransID(): mixed
    {
        return $this->acsTransID;
    }

    /**
     * Get the authentication method associated with the MPI data.
     *
     * @return string|null The authentication method or null if not found.
     */
    public function getAuthMethod(): ?string
    {
        return $this->authMethod;
    }

    /**
     * Get the authentication timestamp associated with the MPI data.
     *
     * @return string|null The authentication timestamp or null if not found.
     */
    public function getAuthTimestamp(): ?string
    {
        return $this->authTimestamp;
    }

    /**
     * Get the authentication status associated with the MPI data.
     *
     * @return string|null The authentication status or null if not found.
     */
    public function getAuthenticationStatus(): ?string
    {
        return $this->authenticationStatus;
    }

    /**
     * Get the Cardholder Authentication Verification Value (CAVV) associated with the MPI data.
     *
     * @return string|null The CAVV or null if not found.
     */
    public function getCavv(): ?string
    {
        return $this->cavv;
    }

    /**
     * Get the Electronic Commerce Indicator (ECI) associated with the MPI data.
     *
     * @return string|null The ECI or null if not found.
     */
    public function getEci(): ?string
    {
        return $this->eci;
    }

    /**
     * Get the message version associated with the MPI data.
     *
     * @return string|null The message version or null if not found.
     */
    public function getMessageVersion(): ?string
    {
        return $this->messageVersion;
    }

    /**
     * Get the ThreeDS session data associated with the MPI data.
     *
     * @return string|null The ThreeDS session data or null if not found.
     */
    public function getThreeDSSessionData(): ?string
    {
        return $this->threeDSSessionData;
    }

    /**
     * Get the ThreeDSv2 token associated with the MPI data.
     *
     * @return string|null The ThreeDSv2 token or null if not found.
     */
    public function getThreeDSv2Token(): ?string
    {
        return $this->threeDSv2Token;
    }
}
