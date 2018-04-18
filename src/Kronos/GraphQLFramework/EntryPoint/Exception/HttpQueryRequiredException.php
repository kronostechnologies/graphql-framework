<?php


namespace Kronos\GraphQLFramework\EntryPoint\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class HttpQueryRequiredException extends FrameworkException
{
	const MSG_GET = 'Through a GET request for GraphQL, a "query" query string parameter must be provided with the request.';
	const MSG_POST = 'Through a POST request for GraphQL, a "query" body parameter must be provided with the request.';

	/**
	 * @param string $method POST or GET
	 * @param Throwable|null $previous
	 */
	public function __construct($method, Throwable $previous = null)
	{
		if ($method === 'GET') {
			parent::__construct(self::MSG_GET, $previous);
		} else {
			parent::__construct(self::MSG_POST, $previous);
		}
	}
}