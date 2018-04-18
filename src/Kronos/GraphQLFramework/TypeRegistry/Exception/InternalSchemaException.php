<?php


namespace Kronos\GraphQLFramework\TypeRegistry\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class InternalSchemaException extends FrameworkException
{
	const MSG = 'While the Type Registry tried calling the constructor of the %typeName% type, it encountered an exception: %underlyingExceptionMsg%';

	public function __construct($typeName, $underlyingExceptionMsg, Throwable $previous = null)
	{
		$message = str_replace('%typeName%', $typeName, self::MSG);
		$message = str_replace('%underlyingExceptionMsg%', $underlyingExceptionMsg, $message);

		parent::__construct($message, 0, $previous);
	}
}