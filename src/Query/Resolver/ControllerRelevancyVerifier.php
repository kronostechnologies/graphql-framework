<?php

/**
 * Class responsible for checking if a controller class has any reason to exist, given
 * a type class name.
 */
class ControllerRelevancyVerifier
{
    /**
     * Full-qualified name of the type class.
     *
     * @var string
     */
    protected $typeClassName;

    /**
     * Full-qualified name of the controller.
     *
     * @var string
     */
    protected $controllerClassName;

    /**
     * Returns false if a controller is considered irrelevant.
     */
    public function isControllerRelevant()
    {
        if (!$this->doesTypeClassExist()) {
            return false;
        }

        $typeConfiguration = $this->getInternalTypeConfiguration();

        return $this->doesConfigurationContainRelevantType($typeConfiguration);
    }

    /**
     * Returns true if the type configuration contains a relevant type.
     *
     * @param array $configuration
     * @return bool
     */
    protected function doesConfigurationContainRelevantType(array $configuration)
    {

    }

    /**
     * Returns the configuration of the type class.
     *
     * @return array
     */
    protected function getInternalTypeConfiguration()
    {

    }

    /**
     * Returns true if the type class exists, or false. Relevant after regenerating a GraphQL schema.
     *
     * @return bool
     */
    protected function doesTypeClassExist()
    {

    }

}