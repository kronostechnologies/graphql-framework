<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\Resolver\Context\GraphQLContext;

abstract class ScalarController
{
	/**
	 * @var GraphQLContext
	 */
	protected $context;

	/**
	 * @param GraphQLContext $context
	 */
	public function __construct(GraphQLContext $context)
	{
		$this->context = $context;
	}

	public abstract function serializeScalarValue($value);
	public abstract function getScalarFromValue($value);
	public abstract function getScalarFromLiteral($value);
}
