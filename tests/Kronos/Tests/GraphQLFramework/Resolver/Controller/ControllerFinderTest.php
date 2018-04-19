<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ControllerFinderTest extends TestCase
{
	const NON_EXISTING_DIR = '/adir/';
	const CONTROLLERS_TEST_DIR = __DIR__ . '/../../../../Mocks/Controllers/';
	const CONTROLLERS_NAMESPACE = '\\Kronos\\Mocks\\Controllers\\';


	const CONTROLLER_FILE_A = self::CONTROLLERS_NAMESPACE . 'AController';
	const CONTROLLER_FILE_B = self::CONTROLLERS_NAMESPACE . 'BController';
	const CONTROLLER_FILE_C = self::CONTROLLERS_NAMESPACE . 'CController';
	const CONTROLLER_FILE_D = self::CONTROLLERS_NAMESPACE . 'SubDir\\DController';

	const OTHER_CLASS_1 = self::CONTROLLERS_NAMESPACE . 'CustomControllerBase';
	const OTHER_CLASS_2 = self::CONTROLLERS_NAMESPACE . 'SubDir\\OtherClass';

	/**
	 * @var LoggerInterface|MockObject
	 */
	protected $loggerMock;

	public function setUp()
	{
		$this->loggerMock = $this->createMock(LoggerInterface::class);
	}

	public function test_NonExistingDir_getAvailableControllerClasses_ThrowsControllerDirNotFound()
	{
		$finder = new ControllerFinder(self::NON_EXISTING_DIR, $this->loggerMock);

		$this->expectException(ControllerDirNotFoundException::class);

		$finder->getPotentialControllerClasses();
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ReturnsRightNumberOfFiles()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertCount(4, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ContainsControllers()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertContains(self::CONTROLLER_FILE_A, $retVal);
		$this->assertContains(self::CONTROLLER_FILE_B, $retVal);
		$this->assertContains(self::CONTROLLER_FILE_C, $retVal);
		$this->assertContains(self::CONTROLLER_FILE_D, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_DoesNotContainOtherClasses()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertNotContains(self::OTHER_CLASS_1, $retVal);
		$this->assertNotContains(self::OTHER_CLASS_2, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_InvalidClassContentLogsWarning()
	{
		$finder = new ControllerFinder(self::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$this->loggerMock->expects($this->once())
			->method('warning')
			->with($this->stringContains('The class name is missing for the controller located at'));

		$finder->getPotentialControllerClasses();
	}

	protected function getMappedFQNs($results)
	{
		return array_map(function ($result) {
			return $result->getFQN();
		}, $results);
	}
}