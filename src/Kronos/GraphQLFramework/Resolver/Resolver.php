<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\Controller\BaseController;
use Kronos\GraphQLFramework\Controller\InterfaceController;
use Kronos\GraphQLFramework\Controller\ScalarController;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerStore;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\InvalidControllerTypeException;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassMethodsReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassMethodFoundException;

class Resolver
{
	const BASE_CONTROLLER_GROUP = 'BaseController';
	const SCALAR_CONTROLLER_GROUP = 'ScalarController';
	const INTERFACE_CONTROLLER_GROUP = 'InterfaceController';

	const BASE_CONTROLLER_FQN = BaseController::class;
	const SCALAR_CONTROLLER_FQN = ScalarController::class;
	const INTERFACE_CONTROLLER_FQN = InterfaceController::class;

	/**
	 * @var FrameworkConfiguration
	 */
	protected $configuration;

	/**
	 * @var ContextUpdater
	 */
	protected $contextUpdater;

	/**
	 * @var ControllerStore
	 */
	protected $controllerStore;

	/**
	 * @param FrameworkConfiguration $configuration
	 */
	public function __construct(FrameworkConfiguration $configuration)
	{
		$this->configuration = $configuration;
		$this->contextUpdater = new ContextUpdater();
		$this->contextUpdater->setConfiguration($configuration);
		$this->controllerStore = new ControllerStore($configuration);
	}

	/**
	 * @param string $typeName
	 * @param string $expectedGroup
	 * @return string
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws InvalidControllerTypeException
	 * @throws NoMatchingControllerFoundException
	 */
	protected function getControllerForTypeExpectingGroup($typeName, $expectedGroup)
	{
		return $this->controllerStore->getControllerForTypeExpectingGroup($typeName, $expectedGroup);
	}

	/**
	 * @param string $typeName
	 * @param string $expectedGroup
	 * @return BaseController
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
	 * @throws InvalidControllerTypeException
	 */
	protected function instanciateControllerForTypeExpectingGroup($typeName, $expectedGroup)
	{
		$controllerFQN = $this->getControllerForTypeExpectingGroup($typeName, $expectedGroup);

		return new $controllerFQN($this->contextUpdater->getActiveContext());
	}

	/**
	 * @param BaseController $controllerInstance
	 * @param string $fieldName
	 * @return mixed
	 * @throws NoClassMethodFoundException
	 * @throws \ReflectionException
	 */
	protected function callFieldMethodForFieldName(BaseController $controllerInstance, $fieldName)
	{
		$expectedMethod = $controllerInstance::getFieldMemberQueryFunctionName($fieldName);

		$methodsReader = new ClassMethodsReader(get_class($controllerInstance));
		$matchingMethod = $methodsReader->getMethodForName($expectedMethod);

		return $controllerInstance->$matchingMethod();
	}

	/**
	 * @param object|null $root
	 * @param array|null $args
	 * @param string $typeName
	 * @param string $fieldName
	 * @return mixed
	 * @throws Context\Exception\ArgumentsMustBeArrayException
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws Controller\Exception\NoMatchingControllerFoundException
	 * @throws MissingFieldResolverException
	 * @throws \ReflectionException
	 * @throws InvalidControllerTypeException
	 */
	public function resolveFieldOfType($root, $args, $typeName, $fieldName)
	{
	    foreach ($this->configuration->getMiddlewares() as $middleware) {
	        $args = $middleware->modifyRequest($args);
	        $root = $middleware->modifyRequest($root);
        }

		$this->contextUpdater->setCurrentResolverPath($root, $args);

		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::BASE_CONTROLLER_GROUP);

		try {
			$result = $this->callFieldMethodForFieldName($controllerInstance, $fieldName);

			foreach ($this->configuration->getMiddlewares() as $middleware) {
			    $result = $middleware->modifyResponse($result);
            }
		} catch (NoClassMethodFoundException $ex) {
			throw new MissingFieldResolverException($typeName, $fieldName);
		}

		return $result;
	}

	/**
	 * @param string $typeName
	 * @param mixed $value
	 * @return mixed
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
	 * @throws InvalidControllerTypeException
	 */
	public function serializeScalarValue($typeName, $value)
	{
		/** @var ScalarController $controllerInstance */
		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::SCALAR_CONTROLLER_GROUP);

		return $controllerInstance->serializeScalarValue($value);
	}

	/**
	 * @param string $typeName
	 * @param mixed $value
	 * @return mixed
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
	 * @throws InvalidControllerTypeException
	 */
	public function getScalarFromValue($typeName, $value)
	{
		/** @var ScalarController $controllerInstance */
		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::SCALAR_CONTROLLER_GROUP);

		return $controllerInstance->getScalarFromValue($value);
	}

	/**
	 * @param string $typeName
	 * @param string $literalValue
	 * @return mixed
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
	 * @throws InvalidControllerTypeException
	 */
	public function getScalarFromLiteral($typeName, $literalValue)
	{
		/** @var ScalarController $controllerInstance */
		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::SCALAR_CONTROLLER_GROUP);

		return $controllerInstance->getScalarFromLiteral($literalValue);
	}

	/**
	 * @param string $typeName
	 * @param mixed $value
	 * @return mixed
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
	 * @throws InvalidControllerTypeException
	 */
	public function resolveInterfaceType($typeName, $value)
	{
		/** @var InterfaceController $controllerInstance */
		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::INTERFACE_CONTROLLER_GROUP);

		return $controllerInstance->resolveInterfaceType($value);
	}
}
