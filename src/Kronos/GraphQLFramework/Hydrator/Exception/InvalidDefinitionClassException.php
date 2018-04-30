<?php


namespace Kronos\GraphQLFramework\Hydrator\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;
use function str_replace;

class InvalidDefinitionClassException extends FrameworkException
{
	const MSG = 'Value type %typeName% cannot be used as a DTO definition.';


	public function __construct($typeName, Throwable $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%typeName%", $typeName, $message);

		parent::__construct($message, $previous);
	}
}
