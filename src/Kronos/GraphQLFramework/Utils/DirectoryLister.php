<?php


namespace Kronos\GraphQLFramework\Utils;


class DirectoryLister
{
	/**
	 * @var string
	 */
	protected $directoryPath;

	/**
	 * @var string[]|null
	 */
	protected $listedFiles;

	/**
	 * @param string $directoryPath
	 */
	public function __construct($directoryPath)
	{
		$this->directoryPath = $directoryPath;
	}

	protected function listFilesUnderDirectory()
	{

	}

	/**
	 * @return string[]
	 */
	public function getAllFiles()
	{

	}
}