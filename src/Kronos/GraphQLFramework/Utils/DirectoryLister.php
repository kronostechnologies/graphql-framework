<?php


namespace Kronos\GraphQLFramework\Utils;


use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

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

	/**
	 * @return string[]
	 */
	protected function getFilesUnderDirectory()
	{
		if (!$this->listedFiles) {
			$recurDirIter = new RecursiveDirectoryIterator($this->directoryPath, FilesystemIterator::SKIP_DOTS);
			$recurIter = new RecursiveIteratorIterator($recurDirIter);

			$this->listedFiles = [];
			foreach ($recurIter as $file) {
				/** @var SplFileInfo $file */
				$this->listedFiles[] = $file->getRealPath();
			}
		}

		return $this->listedFiles;
	}

	/**
	 * @return string[]
	 */
	public function getAllFiles()
	{
		$files = $this->getFilesUnderDirectory();

		return $files;
	}
}