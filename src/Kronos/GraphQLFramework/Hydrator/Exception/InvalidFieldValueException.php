<?php


namespace Kronos\GraphQLFramework\Hydrator\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use function str_replace;

class InvalidFieldValueException extends FrameworkException
{
	const MSG = 'The field %fieldName% to be set for DTO %dtoName% has an invalid value set. It was expected to be filled in with an array, but was instead of type %typeName%.';

	/**
	 * @param string $fieldName
	 * @param string $dtoName
	 * @param string $typeName
	 * @param mixed|null $previous
	 */
	public function __construct($fieldName, $dtoName, $typeName, $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%fieldName%", $fieldName, $message);
		$message = str_replace("%dtoName%", $dtoName, $message);
		$message = str_replace("%typeName%", $typeName, $message);

		parent::__construct($message, $previous);
	}
}
