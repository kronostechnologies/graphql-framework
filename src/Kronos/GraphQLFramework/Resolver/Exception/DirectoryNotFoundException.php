<?php


namespace Kronos\GraphQLFramework\Resolver\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;

class DirectoryNotFoundException extends FrameworkException
{
	const MSG = 'The directory "%dir%" was not found.';

	/**
	 * @var string
	 */
	protected $directory;

	public function __construct($directory, $previous = null)
	{
		$this->directory = $directory;

		$message = str_replace("%dir%", $directory,self::MSG);
		parent::__construct($message, 0, $previous);
	}

	/**
	 * @return string
	 */
	public function getDirectory()
	{
		return $this->directory;
	}
}