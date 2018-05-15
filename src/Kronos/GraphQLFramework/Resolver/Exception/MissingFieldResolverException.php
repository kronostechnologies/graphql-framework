<?php


namespace Kronos\GraphQLFramework\Resolver\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use function str_replace;
use Throwable;

class MissingFieldResolverException extends FrameworkException
{
	const MSG = 'The type "%typeName%" is missing a resolver in its controller for the field "%fieldName%"';

	/**
	 * @param string $typeName
	 * @param string $fieldName
	 * @param Throwable|null $previous
	 */
	public function __construct($typeName, $fieldName, Throwable $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%typeName%", $typeName, $message);
		$message = str_replace("%fieldName%", $fieldName, $message);

		parent::__construct($message, 0, $previous);
	}
}
