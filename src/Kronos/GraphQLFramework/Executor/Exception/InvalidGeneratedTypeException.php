<?php


namespace Kronos\GraphQLFramework\Executor\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use function str_replace;
use Throwable;

class InvalidGeneratedTypeException extends FrameworkException
{
	const MSG = 'Failed to load type "%typeName%". Its definition is incorrect';

	public function __construct($typeName, Throwable $previous = null)
	{
		$message = self::MSG;
		$message = str_replace('%typeName%', $typeName, $message);

		parent::__construct($message, 0, $previous);
	}
}