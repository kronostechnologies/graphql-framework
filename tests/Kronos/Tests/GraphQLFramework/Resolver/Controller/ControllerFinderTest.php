<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use PHPUnit\Framework\TestCase;

class ControllerFinderTest extends TestCase
{
	const NON_EXISTING_DIR = '/adir/';
	const CONTROLLERS_TEST_DIR = __DIR__ . '/../../../../Mocks/Controllers/';

	const CONTROLLER_FILE_A = self::CONTROLLERS_TEST_DIR . 'AController.php';
	const CONTROLLER_FILE_B = self::CONTROLLERS_TEST_DIR . 'BController.php';
	const CONTROLLER_FILE_C = self::CONTROLLERS_TEST_DIR . 'CController.php';
	const CONTROLLER_FILE_D = self::CONTROLLERS_TEST_DIR . 'SubDir/DController.php';

	const OTHER_CLASS_1 = self::CONTROLLERS_TEST_DIR . 'CustomControllerBase.php';
	const OTHER_CLASS_2 = self::CONTROLLERS_TEST_DIR . 'SubDir/OtherClass.php';

	/**
	 * @param string $path
	 * @return bool|string
	 */
	protected function getRealPath($path)
	{
		return realpath($path);
	}

	public function test_NonExistingDir_getAvailableControllerClasses_ThrowsControllerDirNotFound()
	{
		$finder = new ControllerFinder(self::NON_EXISTING_DIR);

		$this->expectException(ControllerDirNotFoundException::class);

		$finder->getPotentialControllerClasses();
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ReturnsRightNumberOfFiles()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR);

		$retVal = $finder->getPotentialControllerClasses();

		$this->assertCount(4, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ContainsControllers()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR);

		$retVal = $finder->getPotentialControllerClasses();

		$this->assertContains($this->getRealPath(self::CONTROLLER_FILE_A), $retVal);
		$this->assertContains($this->getRealPath(self::CONTROLLER_FILE_B), $retVal);
		$this->assertContains($this->getRealPath(self::CONTROLLER_FILE_C), $retVal);
		$this->assertContains($this->getRealPath(self::CONTROLLER_FILE_D), $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_DoesNotContainOtherClasses()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR);

		$retVal = $finder->getPotentialControllerClasses();

		$this->assertNotContains($this->getRealPath(self::OTHER_CLASS_1), $retVal);
		$this->assertNotContains($this->getRealPath(self::OTHER_CLASS_2), $retVal);
	}
}