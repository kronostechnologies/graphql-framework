<?php


namespace Kronos\GraphQLFramework\Resolver\Controller\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class ControllerDirNotFoundException extends FrameworkException
{
	const MSG = 'The specified configured controllers directory "%dir%" was not found.';

	/**
	 * @param string $dir
	 * @param Throwable|null $previous
	 */
	public function __construct($dir, $previous = null)
	{
		$message = str_replace("%dir%", $dir, self::MSG);

		parent::__construct($message, 0, $previous);
	}
}