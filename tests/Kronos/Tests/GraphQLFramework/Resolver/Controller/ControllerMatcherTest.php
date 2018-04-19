<?php


namespace Kronos\Tests\GraphQLFramework\Resolver\Controller;


use Kronos\GraphQLFramework\Resolver\Controller\ControllerMatcher;
use Kronos\GraphQLFramework\Resolver\Controller\Exception\NoMatchingControllerFoundException;
use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReaderResult;
use PHPUnit\Framework\TestCase;

class ControllerMatcherTest extends TestCase
{
	protected $controllerAInfo;
	protected $controllerBInfo;
	protected $controllerCInfo;
	protected $controllerDInfo;

	public function setUp()
	{
		$this->controllerAInfo = new ClassInfoReaderResult(MockData::CONTROLLERS_NAMESPACE, MockData::CONTROLLER_CLASS_A);
		$this->controllerBInfo = new ClassInfoReaderResult(MockData::CONTROLLERS_NAMESPACE, MockData::CONTROLLER_CLASS_B);
		$this->controllerCInfo = new ClassInfoReaderResult(MockData::CONTROLLERS_NAMESPACE, MockData::CONTROLLER_CLASS_C);
		$this->controllerDInfo = new ClassInfoReaderResult(MockData::CONTROLLERS_NAMESPACE_SUBDIR, MockData::CONTROLLER_CLASS_D);
	}

	protected function getKnownControllers()
	{
		return [$this->controllerAInfo, $this->controllerBInfo, $this->controllerCInfo, $this->controllerDInfo];
	}

	public function test_KnownControllersNonExistingType_getControllerForTypeName_ThrowsNoMatchingControllerFoundException()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$this->expectException(NoMatchingControllerFoundException::class);

		$matcher->getControllerForTypeName(MockData::INVALID_TYPE);
	}

	public function test_KnownController_getControllerForTypeNameA_ReturnsControllerAInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_A_TYPE);

		$this->assertSame($this->controllerAInfo, $retVal);
	}

	public function test_KnownController_getControllerForTypeNameB_ReturnsControllerBInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_B_TYPE);

		$this->assertSame($this->controllerBInfo, $retVal);
	}

	public function test_KnownController_getControllerForTypeNameC_ReturnsControllerCInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_C_TYPE);

		$this->assertSame($this->controllerCInfo, $retVal);
	}

	public function test_KnownController_getControllerForTypeNameD_ReturnsControllerDInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_D_TYPE);

		$this->assertSame($this->controllerDInfo, $retVal);
	}

	public function test_KnownController_getControllerForTypeNameDLowercase_ReturnsControllerDInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_D_TYPE_LOWER);

		$this->assertSame($this->controllerDInfo, $retVal);
	}

	public function test_KnownController_getControllerForTypeNameDUntrimmed_ReturnsControllerDInfo()
	{
		$matcher = new ControllerMatcher($this->getKnownControllers());

		$retVal = $matcher->getControllerForTypeName(MockData::CONTROLLER_D_TYPE_UNTRIMMED);

		$this->assertSame($this->controllerDInfo, $retVal);
	}
}