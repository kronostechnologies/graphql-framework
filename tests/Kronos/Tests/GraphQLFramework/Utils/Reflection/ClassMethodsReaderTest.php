<?php


namespace Kronos\Tests\GraphQLFramework\Utils\Reflection;


use Kronos\GraphQLFramework\Utils\Reflection\ClassMethodsReader;
use Kronos\Mocks\Controllers\MixedMethods;
use Kronos\Mocks\Controllers\ProtectedMethodsOnly;
use Kronos\Mocks\Controllers\PublicMethodsOnly;
use PHPUnit\Framework\TestCase;

class ClassMethodsReaderTest extends TestCase
{
	public function test_PublicMethodsOnly_getLowercaseMethodsAssociations_ReturnsAllMethods()
	{
		$reader = new ClassMethodsReader(PublicMethodsOnly::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertCount(3, $retVal);
	}

	public function test_PublicMethodsOnly_getLowercaseMethodsAssociations_ContainsCorrectKeys()
	{
		$reader = new ClassMethodsReader(PublicMethodsOnly::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertArrayHasKey('functiona', $retVal);
		$this->assertArrayHasKey('functionb', $retVal);
		$this->assertArrayHasKey('afinalmethod', $retVal);
	}

	public function test_PublicMethodsOnly_getLowercaseMethodsAssociations_ContainsCorrectValues()
	{
		$reader = new ClassMethodsReader(PublicMethodsOnly::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertContains('functionA', $retVal);
		$this->assertContains('functionB', $retVal);
		$this->assertContains('aFinalMethod', $retVal);
	}

	public function test_PublicMethodsOnly_getLowercaseMethodsAssociations_ContainsCorrectAssociations()
	{
		$reader = new ClassMethodsReader(PublicMethodsOnly::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertSame('functionA', $retVal['functiona']);
		$this->assertSame('functionB', $retVal['functionb']);
		$this->assertSame('aFinalMethod', $retVal['afinalmethod']);
	}

	public function test_ProtectedMethodsOnly_getLowercaseMethodsAssociations_ReturnsNoMethod()
	{
		$reader = new ClassMethodsReader(ProtectedMethodsOnly::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertCount(0, $retVal);
	}

	public function test_MixedMethods_getLowercaseMethodsAssociations_ReturnsPublicMethodsOnly()
	{
		$reader = new ClassMethodsReader(MixedMethods::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertCount(2, $retVal);
	}

	public function test_MixedMethods_getLowercaseMethodsAssociations_ContainsCorrectKeys()
	{
		$reader = new ClassMethodsReader(MixedMethods::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertArrayHasKey('functiona', $retVal);
		$this->assertArrayHasKey('functionc', $retVal);
	}

	public function test_MixedMethods_getLowercaseMethodsAssociations_ContainsCorrectValues()
	{
		$reader = new ClassMethodsReader(MixedMethods::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertContains('functionA', $retVal);
		$this->assertContains('functionC', $retVal);
	}

	public function test_MixedMethods_getLowercaseMethodsAssociations_ContainsCorrectAssociations()
	{
		$reader = new ClassMethodsReader(MixedMethods::class);

		$retVal = $reader->getLowercaseMethodsAssociations();

		$this->assertSame('functionA', $retVal['functiona']);
		$this->assertSame('functionC', $retVal['functionc']);
	}

}