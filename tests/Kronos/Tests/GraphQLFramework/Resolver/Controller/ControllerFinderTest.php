<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ControllerFinder;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\ControllerDirNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ControllerFinderTest extends TestCase
{
	/**
	 * @var LoggerInterface|MockObject
	 */
	protected $loggerMock;

	public function setUp()
	{
		$this->loggerMock = $this->createMock(LoggerInterface::class);
	}

    /**
     * @requires PHP 7
     */
	public function test_NonExistingDir_getAvailableControllerClasses_ThrowsControllerDirNotFound()
	{
		$finder = new ControllerFinder(MockData::NON_EXISTING_DIR, $this->loggerMock);

		$this->expectException(ControllerDirNotFoundException::class);

		$finder->getPotentialControllerClasses();
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ReturnsRightNumberOfFiles()
	{
		$finder = new ControllerFinder(MockData::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertCount(4, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_ContainsControllers()
	{
		$finder = new ControllerFinder(MockData::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertContains(MockData::CONTROLLER_NS_A, $retVal);
		$this->assertContains(MockData::CONTROLLER_NS_B, $retVal);
		$this->assertContains(MockData::CONTROLLER_NS_C, $retVal);
		$this->assertContains(MockData::CONTROLLER_NS_D, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_DoesNotContainOtherClasses()
	{
		$finder = new ControllerFinder(MockData::CONTROLLERS_TEST_DIR, $this->loggerMock);

		$retVal = $finder->getPotentialControllerClasses();
		$retVal = $this->getMappedFQNs($retVal);

		$this->assertNotContains(MockData::OTHER_CLASS_NS_1, $retVal);
		$this->assertNotContains(MockData::OTHER_CLASS_NS_2, $retVal);
	}

	public function test_ControllersTestDir_getAvailableControllerClasses_InvalidClassContentLogsWarning()
	{
		$finder = new ControllerFinder(MockData::CONTROLLERS_TEST_DIR, $this->loggerMock);

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