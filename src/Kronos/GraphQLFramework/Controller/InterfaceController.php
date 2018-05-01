<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\Resolver\Context\GraphQLContext;

abstract class InterfaceController
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

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public abstract function resolveInterfaceType($value);
}
