<?php 
namespace AddonPaymentsSDK\NotificationModel;

class WorkFlowResponse {
    private mixed $id = null;
    private ?string  $name = null;
    private ?int $version = null;


    public function __construct(object $worflowResponse) {

        $this->setId(isset($worflowResponse->id) ? $worflowResponse->id : null);
        $this->setName(isset($worflowResponse->name) ? $worflowResponse->name : null);
        $this->setVersion(isset($worflowResponse->version) ? $worflowResponse->version : null);

      
    }

    public function setId(mixed $var) : void  {
        $this->id = $var;
    }

    public function setName(?string $var)  : void{
        $this->name = $var;
    }
    public function setVersion(?int $var)  : void {
        $this->version = $var;
    }

    /**
     * Get the ID of the workflow response.
     *
     * @return mixed The ID value.
     */
    public function getId()  : mixed{
        return $this->id;
    }

     /**
     * Get the name associated with the workflow response.
     *
     * @return string|null The name value or null if not set.
     */
    public function getName() : ?string{
        return $this->name;
    }

     /**
     * Get the version of the workflow response.
     *
     * @return int|null The version value or null if not set.
     */
    public function getVersion() : ?int {
        return $this->version;
    }

    
}