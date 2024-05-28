<?php
namespace AddonPaymentsSDK\Config\PaySolExtended\Items\Utils;

class Segment
{
    private ?array $segment = null;

     /**
     * Set the IATA departure code for the segment.
     * 
     * @param string $iataDepartureCode The IATA departure code for the segment.
     * @return static Returns the instance of the class for method chaining.
     */
    public function setIataDepartureCode(string $iataDepartureCode): static
    {
        $this->segment['iata_departure_code'] = $iataDepartureCode;
        return $this;
    }

    /**
     * Set the IATA destination code for the segment.
     * 
     * @param string $iataDestinationCode The IATA destination code for the segment.
     * @return static Returns the instance of the class for method chaining.
     */
    public function setIataDestinationCode(string $iataDestinationCode): static
    {
        $this->segment['iata_destination_code'] = $iataDestinationCode;
        return $this;
    }

     /**
     * Get the segment data as an array.
     * 
     * @return array|null Returns the segment data as an array or null if not set.
     */
    public function getSegment(): ?array
    {
        $this->validate();
        return $this->segment;
    }

  /**
     * Validate the segment data.
     * 
     * @return void
     * @throws \InvalidArgumentException If IATA departure code or destination code is missing.
     */
    private function validate()
    {
        if (!isset($this->segment['iata_departure_code'])) {
            throw new \InvalidArgumentException("IATA departure code is required.");
        }
        if (!isset($this->segment['iata_destination_code'])) {
            throw new \InvalidArgumentException("IATA destination code is required.");
        }
    }

}
