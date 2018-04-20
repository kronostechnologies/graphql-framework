<?php


namespace Kronos\Tests\GraphQLFramework\Utils;


use Kronos\GraphQLFramework\Utils\DirectoryLister;
use Kronos\Tests\GraphQLFramework\Utils\Exception\DirectoryNotFoundException;
use PHPUnit\Framework\TestCase;
use function realpath;

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

	/**
	 * @param string $path
	 * @return bool|string
	 */
	protected function getRealPath($path)
	{
		return realpath($path);
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

		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_1), $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_2), $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_3), $retVal);
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

		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_1), $retVal);
		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_2), $retVal);
		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_3), $retVal);
		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_4), $retVal);
	}

	public function test_NonRecursiveDirFilterWithExtensionless_getFilesFilteredByRegex_ReturnsCorrectFilename()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByRegex("/extensionless/");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_1), $retVal);
	}

	public function test_NonRecursiveDirFilterWithNoResult_getFilesFilteredByRegex_ReturnsEmptyArray()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByRegex("/noresult/");

		$this->assertCount(0, $retVal);
	}

	public function test_NonRecursiveDirFilterWithDot_getFilesFilteredByRegex_ReturnsCorrectFilenames()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByRegex("/\./");

		$this->assertCount(2, $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_2), $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_3), $retVal);
	}

	public function test_RecursiveDirFilterWithRtfExtension_getFilesFilteredByRegex_ReturnsCorrectFilename()
	{
		$lister = $this->givenRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByRegex("/\.rtf/");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_4), $retVal);
	}

	public function test_NonRecursiveDirFilterWithPHP_getFilesFilteredByExtension_ReturnsCorrectFilename()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByExtension("php");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_3), $retVal);
	}

	public function test_NonRecursiveDirFilterWithPHPUppercase_getFilesFilteredByExtension_ReturnsCorrectFilename()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByExtension("PHP");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_3), $retVal);
	}

	public function test_NonRecursiveDirFilterWithPNG_getFilesFilteredByExtension_ReturnsCorrectFilename()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByExtension("png");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::NON_RECURSIVE_DIR_FILE_2), $retVal);
	}

	public function test_NonRecursiveDirFilterWithBin_getFilesFilteredByExtension_ReturnsEmptyArray()
	{
		$lister = $this->givenNonRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByExtension("bin");

		$this->assertCount(0, $retVal);
	}

	public function test_RecursiveDirFilterWithRtf_getFilesFilteredByExtension_ReturnsEmptyArray()
	{
		$lister = $this->givenRecursiveDirectoryLister();

		$retVal = $lister->getFilesFilteredByExtension("rtf");

		$this->assertCount(1, $retVal);
		$this->assertContains($this->getRealPath(self::RECURSIVE_DIR_FILE_4), $retVal);
	}

    /**
     * @requires PHP 7
     */
	public function test_NonExistingDirectory_getAllFiles_ThrowsDirectoryNotFoundException()
	{
		$lister = new DirectoryLister("/abc111/aa/bb/cc");

		$this->expectException(DirectoryNotFoundException::class);

		$lister->getAllFiles();
	}
}