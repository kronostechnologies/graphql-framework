<?php


namespace Kronos\GraphQLFramework\Resolver\Context;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Context\Exception\ArgumentsMustBeArrayException;

class ContextUpdater
{
	/**
	 * @var GraphQLContext
	 */
	protected $activeContext;

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
	 * @return GraphQLContext
	 */
	protected function getOrCreateContext()
	{
		if ($this->activeContext === null) {
			$this->activeContext = $this->getDefaultContext();
		}

		return $this->activeContext;
	}

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