<?php


namespace Kronos\GraphQLFramework\Utils;


use FilesystemIterator;
use Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function preg_match;


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
     * @throws DirectoryNotFoundException
     */
	protected function getFilesUnderDirectory()
	{
		if (!$this->listedFiles) {
			try {
				$recurDirIter = new RecursiveDirectoryIterator($this->directoryPath, FilesystemIterator::SKIP_DOTS);
			} catch (\Exception $ex) {
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
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function getAllFiles()
	{
		$files = $this->getFilesUnderDirectory();

		return $files;
	}

	/**
	 * @param string $pattern
	 * @return string[]
	 * @throws DirectoryNotFoundException
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
	 * @throws \Kronos\GraphQLFramework\Resolver\Exception\DirectoryNotFoundException
	 */
	public function getFilesFilteredByExtension($extension)
	{
		return $this->getFilesFilteredByRegex("/\.{$extension}/i");
	}
}