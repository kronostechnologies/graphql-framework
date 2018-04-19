<?php


namespace Kronos\GraphQLFramework\Utils;


use FilesystemIterator;
use Kronos\Tests\GraphQLFramework\Utils\Exception\DirectoryNotFoundException;
use function preg_match;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use UnexpectedValueException;

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
			try {
				$recurDirIter = new RecursiveDirectoryIterator($this->directoryPath, FilesystemIterator::SKIP_DOTS);
			} catch (UnexpectedValueException $ex) {
				throw new DirectoryNotFoundException($this->directoryPath, $ex);
			}

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

	/**
	 * @param string $pattern
	 * @return string[]
	 */
	public function getFilesFilteredByRegex($pattern)
	{
		$files = $this->getFilesUnderDirectory();
		$filteredFiles = array_filter($files, function ($filename) use ($pattern) {
			return preg_match($pattern, $filename);
		});

		return $filteredFiles;
	}

	/**
	 * @param string $extension
	 * @return string[]
	 */
	public function getFilesFilteredByExtension($extension)
	{
		return $this->getFilesFilteredByRegex("/\.{$extension}/i");
	}
}