<?php


namespace Kronos\GraphQLFramework\Executor;


class ExecutorResult
{
	/**
	 * @var string
	 */
	protected $responseText;

	/**
	 * @var \Exception
	 */
	protected $underlyingException;

	/**
	 * @param string $responseText
	 * @param \Exception|null $underlyingException
	 */
	public function __construct($responseText, \Exception $underlyingException = null)
	{
		$this->responseText = $responseText;
		$this->underlyingException = $underlyingException;
	}

	/**
	 * @return string
	 */
	public function getResponseText()
	{
		return $this->responseText;
	}

	/**
	 * @return bool
	 */
	public function hasError()
	{
		return $this->underlyingException !== null;
	}

	/**
	 * @return \Exception
	 */
	public function getUnderlyingException()
	{
		return $this->underlyingException;
	}
}