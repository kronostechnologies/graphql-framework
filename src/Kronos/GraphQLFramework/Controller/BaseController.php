<?php


namespace Kronos\GraphQLFramework\Controller;


use Kronos\GraphQLFramework\ContextAwareTrait;

abstract class BaseController
{
	use ContextAwareTrait;

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