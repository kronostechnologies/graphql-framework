<?php


namespace Kronos\GraphQLFramework\Resolver\Controller\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class NoMatchingControllerFoundException extends FrameworkException
{
	const MSG = 'No matching controller was found for GraphQL type "%typeName%"';

	public function __construct($typeName, Throwable $previous = null)
	{
		$message = str_replace("%typeName%", $typeName, self::MSG);

		parent::__construct($message, 0, $previous);
	}

}