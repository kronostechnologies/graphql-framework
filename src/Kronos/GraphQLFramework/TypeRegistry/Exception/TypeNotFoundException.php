<?php


namespace Kronos\GraphQLFramework\TypeRegistry\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use function str_replace;
use Throwable;

class TypeNotFoundException extends FrameworkException
{
	const MSG = 'The type named %typeName% was requested, but the Type Registry could not detect it.';

	public function __construct($typeName, Throwable $previous = null)
	{
		$message = str_replace('%typeName%', $typeName, self::MSG);

		parent::__construct($message, 0,  $previous);
	}
}