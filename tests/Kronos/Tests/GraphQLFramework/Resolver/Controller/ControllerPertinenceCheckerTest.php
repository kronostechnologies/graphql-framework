<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ControllerPertinenceChecker;
use PHPUnit\Framework\TestCase;

class ControllerPertinenceCheckerTest extends TestCase
{
	public function test_DirectlyInheritBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ControllerPertinenceChecker();

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_A);

		$this->assertTrue($retVal);
	}

	public function test_IndirectlyInheritsBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ControllerPertinenceChecker();

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_D);

		$this->assertTrue($retVal);
	}

	public function test_IrrelevantClass_isControllerPertinent_ReturnsFalse()
	{
		$pertinenceChecker = new ControllerPertinenceChecker();

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::OTHER_CLASS_NS_2);

		$this->assertFalse($retVal);
	}
}