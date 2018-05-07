<?php


namespace Kronos\GraphQLFramework\Hydrator\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;

class FQNDefinitionMissingException extends FrameworkException
{
	const MSG = 'The "fqn" field key is missing at some level for the given DTO definition.';

	public function __construct($previous = null)
	{
		parent::__construct($previous);
	}
}
