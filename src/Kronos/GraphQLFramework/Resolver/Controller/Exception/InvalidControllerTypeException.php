<?php


namespace Kronos\GraphQLFramework\Resolver\Controller\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class InvalidControllerTypeException extends FrameworkException
{
	const MSG = 'The type resolved extends an invalid base controller (it should be "%shouldBeController%", but is "%actualController%")';

	/**
	 * @param string $shouldBeController
	 * @param string $actualController
	 * @param Throwable|null $previous
	 */
	public function __construct($shouldBeController, $actualController, Throwable $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%shouldBeController%", $shouldBeController, $message);
		$message = str_replace("%actualController%", $actualController, $message);

		parent::__construct($message, 0, $previous);
	}
}