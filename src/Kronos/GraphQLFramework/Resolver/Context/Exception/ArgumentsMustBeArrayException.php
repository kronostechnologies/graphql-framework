<?php


namespace Kronos\GraphQLFramework\Resolver\Context\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class ArgumentsMustBeArrayException extends FrameworkException
{
	const MSG = 'The GraphQL arguments passed from the internal library must be an array or null';

	public function __construct(Throwable $previous = null)
	{
		parent::__construct(self::MSG, 0, $previous);
	}
}