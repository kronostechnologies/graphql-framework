<?php

/**
 * Acts as a bridge between the internal GraphQL library. It creates the right controller from the asked
 * entity.
 */
class QueryResolver
{
    /**
     * Mini-service class for fetching the available controllers in the project.
     *
     * @var ControllerFinder
     */
    protected $controllersFinder;

    /**
     * Mini service class for matching an entity name to a controller class name.
     *
     * @var ControllerMatcher
     */
    protected $controllersMatcher;

    /**
     * Dev-mode allows additional logging for controllers relevancy.
     *
     * @var bool
     */
    protected $inDevMode;

    /**
     * Current GraphQL query context.
     *
     * @var GraphQLContext
     */
    protected $context;

    /**
     * ControllerFactory constructor.
     * @param string $controllersNamespace
     * @param string $controllersDirectory
     * @param bool $devMode
     */
    public function __construct($controllersNamespace, $controllersDirectory, $devMode = false)
    {
        $this->controllersMatcher = new ControllerMatcher($controllersNamespace);
        $this->controllersFinder = new ControllerFinder($controllersDirectory, $controllersNamespace);
        $this->inDevMode = $devMode;
    }

    /**
     * @param GraphQLConfiguration $configuration
     * @return QueryResolver
     */
    public static function newFromConfiguration(GraphQLConfiguration $configuration)
    {
        return new self(
            $configuration->getControllersNamespace(),
            $configuration->getControllersDirectory(),
            $configuration->isInDevMode()
        );
    }

    /**
     * Ran before any processing is done for the request.
     */
    public function beginRequest()
    {

    }

    /**
     * Initializes the GraphQLContext.
     *
     * @param string $queryText
     * @param SessionContext $session
     */
    public function initializeContext($queryText, SessionContext $session)
    {
        $this->context = new GraphQLContext($queryText, $session);
    }

    /**
     * Proxy function intended to be called by the base library resolve functions present in the
     * array configured types.
     *
     * Fields that can be resolved simply (e.g. string or number) do not pass through this
     * function at runtime.
     *
     * @param mixed|null $root
     * @param array|null $args
     * @param string $typeName
     * @param string $fieldName
     * @return mixed
     * @throws Exception
     */
    public function resolveFieldOfType($root, $args, $typeName, $fieldName)
    {
        // Updates context
        $this->context = $this->context
            ->withCurrentArguments(($args === null) ? ([]) : ($args))
            ->withCurrentRootContext($root);

        $controllerFQN = $this->getControllerFQNForTypeName($typeName);
        $this->validateControllerBaseClass($controllerFQN);
        $resolveFunctionName = $controllerFQN::getFieldMemberQueryFunctionName($fieldName);

        $this->validateControllerFieldFunctionExists($controllerFQN, $resolveFunctionName);

        /** @var BaseController $controllerInstance */
        $controllerInstance = new $controllerFQN($this->context);

        return $controllerInstance->$resolveFunctionName();
    }

    /**
     * @param string $typeName
     * @return string
     *
     * @throws Exception
     */
    protected function getControllerFQNForTypeName($typeName)
    {
        $controllers = $this->controllersFinder->getAvailableControllerClasses();
        $controllerFQN = $this->controllersMatcher->getControllerNameForEntity($typeName);

        $matchingControllers = array_filter($controllers, function ($current) use ($controllerFQN) {
            return ($current === $controllerFQN);
        });
        $controllerFound = count($matchingControllers) > 0;

        if (!$controllerFound) {
            throw new Exception("No controller found matching entity {$typeName}. It should be located at {$controllerFQN}");
        }

        return array_shift($matchingControllers);
    }

    /**
     * @param string $controllerFQN
     * @throws Exception
     */
    protected function validateControllerBaseClass($controllerFQN)
    {
        $validityVerifier = new ControllerValidityVerifier($controllerFQN);
        if (!$validityVerifier->hasCorrectBaseClass()) {
            $mustImplement = ControllerValidityVerifier::BASE_CONTROLLER_FQN;
            throw new Exception("Controller {$controllerFQN} was found correctly, but it does not extend {$mustImplement}");
        }
    }

    /**
     * @param string $controllerFQN
     * @param string $functionName
     * @throws Exception
     */
    protected function validateControllerFieldFunctionExists($controllerFQN, $functionName)
    {
        $validityVerifier = new ControllerValidityVerifier($controllerFQN);
        if (!$validityVerifier->hasFunctionNamed($functionName)) {
            throw new Exception("Controller {$controllerFQN} should contain a function named {$functionName}");
        }
    }
}