<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\Hydrator\DTOHydrator;
use Kronos\GraphQLFramework\Resolver\Context\GraphQLContext;

abstract class BaseController
{
	/**
	 * @var GraphQLContext
	 */
	protected $context;

	/**
	 * @var DTOHydrator
	 */
	protected $hydrator;

	/**
	 * @param GraphQLContext $context
	 * @param DTOHydrator|null $hydrator
	 */
	public function __construct(GraphQLContext $context, DTOHydrator $hydrator = null)
	{
		$this->context = $context;
		$this->hydrator = $hydrator ?: new DTOHydrator();
	}

	/**
	 * Returns the name of the function that should be executed for resolving a field. It does not check if the
	 * function exists.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	public static function getFieldMemberQueryFunctionName($fieldName)
	{
		return "get{$fieldName}";
	}
}
