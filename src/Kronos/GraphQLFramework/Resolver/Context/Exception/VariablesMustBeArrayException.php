<?php


namespace Kronos\GraphQLFramework\Resolver\Context\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;

class VariablesMustBeArrayException extends FrameworkException
{
	const MSG = 'The GraphQL variables passed from the executor must be an array or null';

	public function __construct($previous = null)
	{
		parent::__construct(self::MSG, 0, $previous);
	}
}