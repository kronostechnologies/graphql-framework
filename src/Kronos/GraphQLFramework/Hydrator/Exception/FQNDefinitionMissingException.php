<?php


namespace Kronos\GraphQLFramework\Hydrator\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class FQNDefinitionMissingException extends FrameworkException
{
	const MSG = 'The "fqn" field key is missing at some level for the given DTO definition.';

	public function __construct(Throwable $previous = null)
	{
		parent::__construct($previous);
	}
}
