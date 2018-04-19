<?php


namespace Kronos\GraphQLFramework\Resolver;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\ContextUpdater;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerMatcher;
use Kronos\GraphQLFramework\Resolver\Controller\ControllerPertinenceChecker;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use Kronos\Tests\GraphQLFramework\BaseController;

class Resolver
{
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
	protected $pertinentControllers;

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
	protected function getPertinentControllers()
	{
		if ($this->pertinentControllers === null) {
			$finder = new ControllerFinder($this->configuration->getControllersDirectory(),
				$this->configuration->getLogger());
			$controllerClasses = $finder->getPotentialControllerClasses();

			$pertinenceChecker = new ControllerPertinenceChecker();
			$this->pertinentControllers = $pertinenceChecker->getPertinentControllers($controllerClasses);
		}

		return $this->pertinentControllers;
	}

	/**
	 * @param string $typeName
	 * @return string
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws Controller\Exception\NoMatchingControllerFoundException
	 */
	protected function getControllerForType($typeName)
	{
		$pertinentControllers = $this->getPertinentControllers();
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

		return new $controllerFQN($this->configuration);
	}

	/**
	 * @param object|null $root
	 * @param array|null $args
	 * @param string $typeName
	 * @param string $fieldName
	 * @throws Context\Exception\ArgumentsMustBeArrayException
	 * @throws Controller\Exception\ControllerDirNotFoundException
	 * @throws Controller\Exception\NoMatchingControllerFoundException
	 */
	public function resolveFieldOfType($root, $args, $typeName, $fieldName)
	{
		$this->contextUpdater->setCurrentResolverPath($root, $args);

		$controller = $this->instanciateControllerForType($typeName);
	}
}