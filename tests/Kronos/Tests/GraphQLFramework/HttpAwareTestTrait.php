<?php


namespace Kronos\Tests\GraphQLFramework;


use GuzzleHttp\Psr7\ServerRequest;

trait HttpAwareTestTrait
{
	/**
	 * @return ServerRequest
	 */
	protected function getRequest()
	{
		return new ServerRequest('GET', '');
	}

	/**
	 * @return ServerRequest
	 */
	protected function postRequest()
	{
		return new ServerRequest('POST', '');
	}
}