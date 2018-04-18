<?php


namespace Kronos\GraphQLFramework\Resolver\Context;


use Kronos\GraphQLFramework\FrameworkConfiguration;

/**
 * Immutable context object available to the framework user.
 *
 * @package Kronos\GraphQLFramework\Resolver
 */
class GraphQLContext
{
	/**
	 * @var GraphQLConfiguration
	 */
	protected $configuration;

	/**
	 * @var array
	 */
	protected $currentArguments;

	/**
	 * @var object|null
	 */
	protected $currentParentObject;

	/**
	 * @var string
	 */
	protected $fullQueryString;

	/**
	 * @var array
	 */
	protected $variables;

	/**
	 * Returns the configuration object initially provided to the entry point. Always defined as a GraphQLConfiguration
	 * or implementor of it.
	 *
	 * @return GraphQLConfiguration
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * Returns the current arguments provided to the current request in the resolver. Always returned in an array
	 * format.
	 *
	 * @return array
	 */
	public function getCurrentArguments()
	{
		return $this->currentArguments;
	}

	/**
	 * Defines the caller of the current point where the request is at in the resolver. This will be null if at
	 * the root of the query. Also referred to as $root directly in the underlying library resolvers.
	 *
	 * @return object|null
	 */
	public function getCurrentParentObject()
	{
		return $this->currentParentObject;
	}

	/**
	 * Returns the full original query string which triggered the GraphQL query. Always defined as a string.
	 *
	 * @return string
	 */
	public function getFullQueryString()
	{
		return $this->fullQueryString;
	}

	/**
	 * Variables provided to the initial entry point. Always defined as an array.
	 *
	 * @return array
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * Returns a NEW instance of the GraphQL Context with the given configuration. This will not overwrite the
	 * existing GraphQLContext by itself.
	 *
	 * @param FrameworkConfiguration $configuration
	 * @return GraphQLContext
	 */
	public function withConfiguration(FrameworkConfiguration $configuration)
	{
		$inst = clone $this;
		$inst->configuration = $configuration;

		return $inst;
	}

	/**
	 * Returns a NEW instance of the GraphQL Context with the given current arguments. This will not overwrite the
	 * existing GraphQLContext by itself.
	 *
	 * @param array $currentArguments
	 * @return GraphQLContext
	 */
	public function withCurrentArguments(array $currentArguments)
	{
		$inst = clone $this;
		$inst->currentArguments = $currentArguments;

		return $inst;
	}

	/**
	 * Returns a NEW instance of the GraphQL Context with the given current parent object. This will not overwrite the
	 * existing GraphQLContext by itself.
	 *
	 * @param object|null $currentParentObject
	 * @return GraphQLContext
	 */
	public function withCurrentParentObject($currentParentObject)
	{
		$inst = clone $this;
		$inst->currentParentObject = $currentParentObject;

		return $inst;
	}

	/**
	 * Returns a NEW instance of the GraphQL Context with the given full query string. This will not overwrite the
	 * existing GraphQLContext by itself.
	 *
	 * @param string $fullQueryString
	 * @return GraphQLContext
	 */
	public function withFullQueryString($fullQueryString)
	{
		$inst = clone $this;
		$inst->fullQueryString = $fullQueryString;

		return $inst;
	}

	/**
	 * Returns a NEW instance of the GraphQL Context with the given variables. This will not overwrite the
	 * existing GraphQLContext by itself.
	 *
	 * @param array $variables
	 * @return GraphQLContext
	 */
	public function withVariables(array $variables)
	{
		$inst = clone $this;
		$inst->variables = $variables;

		return $inst;
	}
}
