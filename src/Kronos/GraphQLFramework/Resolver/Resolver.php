<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\Controller\BaseController;
use Kronos\GraphQLFramework\Controller\ScalarController;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerMatcher;
use Kronos\GraphQLFramework\Resolver\Controller\ClassInheritanceValidator;
use Kronos\GraphQLFramework\Resolver\Exception\MissingFieldResolverException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use Kronos\GraphQLFramework\Utils\Reflection\ClassMethodsReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassMethodFoundException;

class Resolver
{

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
	protected $unfilteredClassInfoReaderResults;

	/**
	 * @var ClassInfoReaderResult[]
	 */
	protected $baseControllerClasses;

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
		if ($this->unfilteredClassInfoReaderResults === null) {
			$finder = new ControllerFinder($this->configuration->getControllersDirectory(),
				$this->configuration->getLogger());
			$this->unfilteredClassInfoReaderResults = $finder->getPotentialControllerClasses();
		}

		return $this->unfilteredClassInfoReaderResults;
	}

	/**
	 * @param string $className
	 * @return ClassInfoReaderResult[]
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getControllersInheritingClassName($className)
	{
		$unfilteredClasses = $this->getControllerFinderResults();

		$pertinenceChecker = new ClassInheritanceValidator($className);

		return $pertinenceChecker->getPertinentControllers($unfilteredClasses);
	}

	/**
	 * @return ClassInfoReaderResult[]
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getBaseControllers()
	{
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
	protected function getControllerForType($typeName)
	{
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
	protected function instanciateControllerForType($typeName)
	{
		$controllerFQN = $this->getControllerForType($typeName);

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

		$controllerInstance = $this->instanciateControllerForType($typeName);

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