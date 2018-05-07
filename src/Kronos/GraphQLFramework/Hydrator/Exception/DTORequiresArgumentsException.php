<?php


namespace Kronos\GraphQLFramework\Hydrator\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use function str_replace;

class DTORequiresArgumentsException extends FrameworkException
{
	const MSG = 'The DTO "%dtoFQN%" abnormally requires non-optional constructor arguments.';

	public function __construct($dtoFQN, $previous = null)
	{
		$message = self::MSG;

		$message = str_replace("%dtoFQN%", $dtoFQN, $message);

		parent::__construct($message, $previous);
	}
}
