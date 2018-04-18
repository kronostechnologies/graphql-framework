<?php


namespace Kronos\Tests\GraphQLFramework\Utils;


use Kronos\GraphQLFramework\Utils\DirectoryLister;
use PHPUnit\Framework\TestCase;

class DirectoryListerTest extends TestCase
{
	const RECURSIVE_DIR = __DIR__ . '/../../../Mocks/Utils/Directories/Recursive/';
	const RECURSIVE_DIR_FILE_1 = self::RECURSIVE_DIR . 'afile.php';
	const RECURSIVE_DIR_FILE_2 = self::RECURSIVE_DIR . 'bfile.txt';
	const RECURSIVE_DIR_FILE_3 = self::RECURSIVE_DIR . 'cfile.wav';
	const RECURSIVE_DIR_FILE_4 = self::RECURSIVE_DIR . 'subdir/a/dfile.rtf';
	const RECURSIVE_DIR_COUNT = 4;

	const NON_RECURSIVE_DIR = __DIR__ . '/../../../Mocks/Utils/Directories/Simple/';
	const NON_RECURSIVE_DIR_FILE_1 = self::NON_RECURSIVE_DIR . 'extensionless';
	const NON_RECURSIVE_DIR_FILE_2 = self::NON_RECURSIVE_DIR . 'img.png';
	const NON_RECURSIVE_DIR_FILE_3 = self::NON_RECURSIVE_DIR . 'phpscript.php';
	const NON_RECURSIVE_DIR_COUNT = 3;

	/**
	 * @return DirectoryLister
	 */
	protected function givenRecursiveDirectoryLister()
	{
		return new DirectoryLister(self::RECURSIVE_DIR);
	}

	/**
	 * @return DirectoryLister
	 */
	protected function givenNonRecursiveDirectoryLister()
	{
		return new DirectoryLister(self::NON_RECURSIVE_DIR);
	}

	public function test_NonRecursiveDirectory_getAllFiles_ReturnsRightNumberOfFiles()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = count($lister->getAllFiles());

		$this->assertSame(self::NON_RECURSIVE_DIR_COUNT, $retVal);
	}

	public function test_NonRecursiveDirectory_getAllFiles_ReturnsRightFileNames()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getAllFiles();

		$this->assertContains(self::NON_RECURSIVE_DIR_FILE_1, $retVal);
		$this->assertContains(self::NON_RECURSIVE_DIR_FILE_2, $retVal);
		$this->assertContains(self::NON_RECURSIVE_DIR_FILE_3, $retVal);
	}

	public function test_RecursiveDirectory_getAllFiles_ReturnsRightNumberOfFiles()
	{
		$lister = $this->givenRecursiveDirectoryLister();

		$retVal = count($lister->getAllFiles());

		$this->assertSame(self::RECURSIVE_DIR_COUNT, $retVal);
	}

	public function test_RecursiveDirectory_getAllFiles_ReturnsRightFileNames()
	{
		$lister = $this->givenRecursiveDirectoryLister();

		$retVal = $lister->getAllFiles();

		$this->assertContains(self::RECURSIVE_DIR_FILE_1, $retVal);
		$this->assertContains(self::RECURSIVE_DIR_FILE_2, $retVal);
		$this->assertContains(self::RECURSIVE_DIR_FILE_3, $retVal);
		$this->assertContains(self::RECURSIVE_DIR_FILE_4, $retVal);
	}
}