<?php


namespace Kronos\GraphQLFramework\Exception;


interface ClientDisplayableExceptionInterface
{
	/**
	 * The status code is always sent with an exception. This can help HTTP clients.
	 *
	 * @return int
	 */
	public function getClientHttpStatusCode();

	/**
	 * A unique identifier code for the exception that occurred.
	 *
	 * @return string
	 */
	public function getClientErrorCode();

	/**
	 * A description helping in identifiying the cause of the exception.
	 *
	 * @return string
	 */
	public function getClientErrorDescription();
}
