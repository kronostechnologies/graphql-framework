<?php


namespace Kronos\GraphQLFramework\Utils\Reflection\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class NoClassMethodFoundException extends FrameworkException
{
	const MSG = 'No class method was found for %methodName% in %classFQN%.';

	/**
	 * @param string $methodName
	 * @param string $classFQN
	 * @param Throwable|null $previous
	 */
	public function __construct($methodName, $classFQN, Throwable $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%methodName%", $methodName, $message);
		$message = str_replace("%classFQN%", $classFQN, $message);

		parent::__construct($message, 0, $previous);
	}
}