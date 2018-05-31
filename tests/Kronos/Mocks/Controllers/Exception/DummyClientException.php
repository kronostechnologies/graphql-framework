<?php


namespace Kronos\Mocks\Controllers\Exception;


use Kronos\GraphQLFramework\Exception\ClientDisplayableExceptionInterface;

class DummyClientException extends \Exception implements ClientDisplayableExceptionInterface
{
	const CODE = 'DummyClientException';
	const MSG = 'Dummy exception message.';
	const STATUS_CODE = 501;

	/**
	 * The status code is always sent with an exception. This can help HTTP clients.
	 *
	 * @return int
	 */
	public function getClientHttpStatusCode()
	{
		return self::STATUS_CODE;
	}

	/**
	 * A unique identifier code for the exception that occurred.
	 *
	 * @return string
	 */
	public function getClientErrorCode()
	{
		return self::CODE;
	}

	/**
	 * A description helping in identifiying the cause of the exception.
	 *
	 * @return string
	 */
	public function getClientErrorDescription()
	{
		return self::MSG;
	}
}
