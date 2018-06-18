<?php


namespace Kronos\GraphQLFramework\Resolver\Context;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\Exception\ArgumentsMustBeArrayException;
use Kronos\GraphQLFramework\Resolver\Context\Exception\VariablesMustBeArrayException;
use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;

class ContextUpdater
{
	/**
	 * @var GraphQLContext
	 */
	protected $activeContext;

	/**
	 * @param object|null $root
	 * @param array|null $arguments
	 * @throws ArgumentsMustBeArrayException
	 */
	public function setCurrentResolverPath($root, $arguments)
	{
		$argumentsToSet = $arguments;

		if ($argumentsToSet !== null && !is_array($argumentsToSet)) {
			throw new ArgumentsMustBeArrayException();
		}

		if ($argumentsToSet === null) {
			$argumentsToSet = [];
		}

		$this->activeContext = $this->getOrCreateContext()
			->withCurrentArguments($argumentsToSet)
			->withCurrentParentObject($root);
	}

	/**
	 * @param FrameworkConfiguration $configuration
	 */
	public function setConfiguration(FrameworkConfiguration $configuration)
	{
		$this->activeContext = $this->getOrCreateContext()
			->withConfiguration($configuration);
	}

	/**
	 * @param string $fullQueryString
	 * @param array|null $variables
	 * @throws VariablesMustBeArrayException
	 */
	public function setInitialData($fullQueryString, $variables)
	{
		$variablesToSet = $variables;

		if ($variablesToSet !== null && !is_array($variablesToSet)) {
			throw new VariablesMustBeArrayException();
		}

		if ($variablesToSet === null) {
			$variablesToSet = [];
		}

		$this->activeContext = $this->getOrCreateContext()
			->withFullQueryString($fullQueryString ?: "")
			->withVariables($variablesToSet);
	}

    /**
     * @param AutomatedTypeRegistry $typeRegistry
     */
	public function setTypeRegistry(AutomatedTypeRegistry $typeRegistry)
    {
        $this->activeContext = $this->getOrCreateContext()
            ->withTypeRegistry($typeRegistry);
    }

	/**
	 * @return GraphQLContext
	 */
	protected function getOrCreateContext()
	{
		if ($this->activeContext === null) {
			$this->activeContext = $this->getDefaultContext();
		}

		return $this->activeContext;
	}

	/**
	 * @return GraphQLContext
	 */
	protected function getDefaultContext()
	{
		$initialContext = new GraphQLContext();

		return $initialContext
			->withConfiguration(new FrameworkConfiguration())
			->withCurrentArguments([])
			->withCurrentParentObject(null)
			->withFullQueryString("")
			->withVariables([]);
	}

	/**
	 * @return GraphQLContext
	 */
	public function getActiveContext()
	{
		return $this->getOrCreateContext();
	}
}
