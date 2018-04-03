<?php

/**
 * Verifies if a user-defined controller is valid with a reflection helper.
 */
class ControllerValidityVerifier
{
    const BASE_CONTROLLER_FQN = BaseController::class;

    /**
     * @var string
     */
    protected $controllerFQN;

    /**
     * @param string $controllerFQN
     */
    public function __construct($controllerFQN)
    {
        $this->controllerFQN = $controllerFQN;
    }

    /**
     * Returns true if the controller extends the correct base class (in BASE_CONTROLLER_FQN).
     *
     * @return bool
     */
    public function hasCorrectBaseClass()
    {

    }

    /**
     * Returns true if the controller contains the given function.
     *
     * @param string $functionName
     * @return bool
     */
    public function hasFunctionNamed($functionName)
    {

    }
}