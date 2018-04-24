<?php


namespace Kronos\GraphQLFramework;


use Kronos\GraphQLFramework\Resolver\Context\GraphQLContext;

trait ContextAwareTrait
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

}