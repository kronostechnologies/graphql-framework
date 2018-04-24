<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ClassInheritanceValidator;
use Kronos\GraphQLFramework\Resolver\Resolver;
use PHPUnit\Framework\TestCase;

class ClassInheritanceValidatorTest extends TestCase
{
	public function test_DirectlyInheritBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceValidator(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_A);

		$this->assertTrue($retVal);
	}

	public function test_IndirectlyInheritsBaseController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceValidator(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::CONTROLLER_NS_D);

		$this->assertTrue($retVal);
	}

	public function test_IrrelevantClass_isControllerPertinent_ReturnsFalse()
	{
		$pertinenceChecker = new ClassInheritanceValidator(Resolver::BASE_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::OTHER_CLASS_NS_2);

		$this->assertFalse($retVal);
	}

	public function test_DirectlyInheritScalarController_isControllerPertinent_ReturnsTrue()
	{
		$pertinenceChecker = new ClassInheritanceValidator(Resolver::SCALAR_CONTROLLER_FQN);

		$retVal = $pertinenceChecker->isControllerPertinent(MockData::SCALAR_CONTROLLER_NS);

		$this->assertTrue($retVal);
	}
}