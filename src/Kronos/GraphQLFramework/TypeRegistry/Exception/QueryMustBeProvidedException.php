<?php


namespace Kronos\GraphQLFramework\TypeRegistry\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class QueryMustBeProvidedException extends FrameworkException
{
	const MSG = 'The TypeRegistry could not find the Query type. It must be provided as stated by the RFC.';

	public function __construct(Throwable $previous = null)
	{
		parent::__construct(self::MSG, 0, $previous);
	}
}