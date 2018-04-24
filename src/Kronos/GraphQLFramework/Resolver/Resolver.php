<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\Controller\BaseController;
use Kronos\GraphQLFramework\Controller\ScalarController;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerMatcher;
use Kronos\GraphQLFramework\Resolver\Controller\ClassInheritanceFilterer;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use Kronos\GraphQLFramework\Utils\Reflection\ClassMethodsReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassMethodFoundException;

class Resolver
{
	const BASE_CONTROLLER_GROUP = 'BaseController';
	const SCALAR_CONTROLLER_GROUP = 'ScalarController';

	const BASE_CONTROLLER_FQN = BaseController::class;
	const SCALAR_CONTROLLER_FQN = ScalarController::class;

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
	 * @return string
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws Controller\Exception\NoMatchingControllerFoundException
	 */
	protected function getControllerForTypeExpectingGroup($typeName, $expectedGroup)
	{
		// ToDo: You were here (iterate & throw exception if expected group mismatches)
		$this->getGroupedControllers();
		$pertinentControllers = $this->getBaseControllers();
		$controllerMatcher = new ControllerMatcher($pertinentControllers);

		return $controllerMatcher->getControllerForTypeName($typeName)->getFQN();
	}

	/**
	 * @param string $typeName
	 * @return BaseController
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws Controller\Exception\NoMatchingControllerFoundException
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

		$controllerInstance = $this->instanciateControllerForType($typeName, self::BASE_CONTROLLER_GROUP);

		try {
			$result = $this->callFieldMethodForFieldName($controllerInstance, $fieldName);
		} catch (NoClassMethodFoundException $ex) {
			throw new MissingFieldResolverException($typeName, $fieldName);
		}

		return $result;
	}

	public function serializeScalarValue($typeName, $value)
	{
		// ToDo: Stub
	}

	public function getScalarFromValue($typeName, $value)
	{
		// ToDo: Stub
	}

	public function getScalarFromLiteral($typeName, $literalValue)
	{
		// ToDo: Stub
	}

	public function resolveInterfaceType($typeName, $value)
	{
		// ToDo: Stub
	}
}