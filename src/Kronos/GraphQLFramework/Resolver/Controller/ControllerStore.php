<?php


namespace Kronos\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Controller;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\InvalidControllerTypeException;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Resolver\Resolver;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;

class ControllerStore
{
	/**
	 * @var FrameworkConfiguration
	 */
	protected $configuration;

	/**
	 * @var ClassInfoReaderResult[]|null
	 */
	protected $controllerClasses;

	/**
	 * @var ClassInfoReaderResult[][]
	 */
	protected $groupedControllers;

	/**
	 * @var ClassInfoReaderResult[]
	 */
	protected $potentialControllerClasses;

	/**
	 * @param FrameworkConfiguration $configuration
	 */
	public function __construct(FrameworkConfiguration $configuration)
	{
		$this->configuration = $configuration;
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
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getControllersInheritingClassName($className)
	{
		$unfilteredClasses = $this->getControllerFinderResults();

		$inheritanceFilterer = new ClassInheritanceFilterer($className);

		return $inheritanceFilterer->getFilteredResults($unfilteredClasses);
	}

	/**
	 * @return ClassInfoReaderResult[]
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	protected function getGroupedControllers()
	{
		if ($this->groupedControllers === null) {
			$this->groupedControllers = [];
			$this->groupedControllers[Resolver::BASE_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(Resolver::BASE_CONTROLLER_FQN);
			$this->groupedControllers[Resolver::SCALAR_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(Resolver::SCALAR_CONTROLLER_FQN);
			$this->groupedControllers[Resolver::INTERFACE_CONTROLLER_GROUP] = $this->getControllersInheritingClassName(Resolver::INTERFACE_CONTROLLER_FQN);
		}

		return $this->groupedControllers;
	}

	/**
	 * @param string $typeName
	 * @param string $expectedGroup
	 * @return string
	 * @throws InvalidControllerTypeException
	 * @throws NoMatchingControllerFoundException
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 */
	public function getControllerForTypeExpectingGroup($typeName, $expectedGroup)
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
}