<?php

/**
 * Matches a controller FQN to an entity name.
 */
class ControllerMatcher
{
    const CONTROLLER_SUFFIX = 'Controller';

    /**
     * Namespace for $controllersDirectory.
     *
     * @var string
     */
    protected $controllersNamespace;

    /**
     * @param string $controllersNamespace
     */
    public function __construct($controllersNamespace)
    {
        $this->controllersNamespace = $controllersNamespace;
    }

    /**
     * Returns the name wanted for the controller, given the entity name.
     *
     * @param string $entityName
     * @return string
     */
    public function getControllerNameForEntity($entityName)
    {

    }
}