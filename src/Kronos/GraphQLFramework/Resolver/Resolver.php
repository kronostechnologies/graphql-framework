<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\Controller\BaseController;
use Kronos\GraphQLFramework\Controller\InterfaceController;
use Kronos\GraphQLFramework\Controller\ScalarController;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerMatcher;
use Kronos\GraphQLFramework\Resolver\Controller\ClassInheritanceFilterer;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\InvalidControllerTypeException;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
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
	 * @var ClassInfoReaderResult[]
	 */
	protected $baseControllerClasses;

	/**
	 * @var ClassInfoReaderResult[]
	 */
	protected $potentialControllerClasses;

	/**
	 * @var string[]
	 */
	protected $groupedControllers;

	/**
	 * @param FrameworkConfiguration $configuration
	 */
	public function __construct(FrameworkConfiguration $configuration)
	{
		$this->configuration = $configuration;
		$this->contextUpdater = new ContextUpdater();
		$this->contextUpdater->setConfiguration($configuration);
	}

	/**
	 * @return ClassInfoReaderResult[]
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getControllerFinderResults()
	{
		if ($this->potentialControllerClasses === null) {
			$controllerFinder = new ControllerFinder($this->configuration->getControllersDirectory(),
				$this->configuration->getLogger());
			$this->potentialControllerClasses = $controllerFinder->getPotentialControllerClasses();
		}

		return $this->potentialControllerClasses;
	}

	/**
	 * @param string $className
	 * @return ClassInfoReaderResult[]
	 */
	protected function getControllersInheritingClassName($className)
	{
		$unfilteredClasses = $this->getControllerFinderResults();

		$inheritanceFilterer = new ClassInheritanceFilterer($className);

		return $inheritanceFilterer->getFilteredResults($unfilteredClasses);
	}

	/**
	 * @return ClassInfoReaderResult[]
	 */
	protected function getGroupedControllers()
	{
		if ($this->groupedControllers === null) {
			$this->groupedControllers = [];
			$this->groupedControllers[self::BASE_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(self::BASE_CONTROLLER_FQN);
			$this->groupedControllers[self::SCALAR_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(self::SCALAR_CONTROLLER_FQN);
			$this->groupedControllers[self::INTERFACE_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(self::INTERFACE_CONTROLLER_FQN);
		}

		return $this->groupedControllers;
	}

	/**
	 * @return ClassInfoReaderResult[]
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getBaseControllers()
	{
		// ToDo: You were here too (delete this function)
		if ($this->baseControllerClasses === null) {
			$this->baseControllerClasses = $this->getControllersInheritingClassName(self::BASE_CONTROLLER_FQN);
		}

		return $this->baseControllerClasses;
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
		foreach ($this->getGroupedControllers() as $groupName => $controllers) {
			/** @var ClassInfoReaderResult[] $controllers */
			$controllerMatcher = new ControllerMatcher($controllers);


			$matchingController = $controllerMatcher->getControllerForTypeName($typeName);

			if ($matchingController !== null) {
				if ($expectedGroup !== $groupName) {
					throw new InvalidControllerTypeException($expectedGroup, $groupName);
				} else {
					return $matchingController->getFQN();
				}
			}
		}

		throw new NoMatchingControllerFoundException($typeName);
	}

	/**
	 * @param string $typeName
	 * @param string $expectedGroup
	 * @return BaseController
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws NoMatchingControllerFoundException
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
	 */
	public function resolveFieldOfType($root, $args, $typeName, $fieldName)
	{
		$this->contextUpdater->setCurrentResolverPath($root, $args);

		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::BASE_CONTROLLER_GROUP);

		try {
			$result = $this->callFieldMethodForFieldName($controllerInstance, $fieldName);
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
	 */
	public function resolveInterfaceType($typeName, $value)
	{
		/** @var InterfaceController $controllerInstance */
		$controllerInstance = $this->instanciateControllerForTypeExpectingGroup($typeName, self::INTERFACE_CONTROLLER_GROUP);

		return $controllerInstance->resolveInterfaceType($value);
	}
}