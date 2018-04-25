<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ClassInheritanceFilterer;
use Kronos\GraphQLFramework\Resolver\Resolver;
use PHPUnit\Framework\TestCase;

class ClassInheritanceFiltererTest extends TestCase
{
	public function test_DirectlyInheritBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceFilterer(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_A);

		$this->assertTrue($retVal);
	}

	public function test_IndirectlyInheritsBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceFilterer(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_D);

		$this->assertTrue($retVal);
	}

	public function test_IrrelevantClass_isControllerPertinent_ReturnsFalse()
	{
		$pertinenceChecker = new ClassInheritanceFilterer(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::OTHER_CLASS_NS_2);

		$this->assertFalse($retVal);
	}

	public function test_DirectlyInheritScalarController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceFilterer(Resolver::SCALAR_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_COLOR);

		$this->assertTrue($retVal);
	}
}