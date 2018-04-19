<?php


namespace Kronos\Tests\GraphQLFramework\Executor;


use GraphQL\Type\Definition\ObjectType;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExecutorTest extends TestCase
{
	/**
	 * @var MockObject|AutomatedTypeRegistry
	 */
	protected $typeRegistryMock;

	public function setUp()
	{
		$this->typeRegistryMock = $this->createMock(AutomatedTypeRegistry::class);
	}

	public function test_InvalidQueryType_executeQuery_ResultHasError()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$result = $executor->executeQuery("", []);

		$this->assertTrue($result->hasError());
	}

	public function test_ValidQueryType_executeQuery_LoadsQueryType()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock
			->expects($this->once())
			->method('getQueryType')
			->willReturn(new ObjectType([ 'name' => 'Query' ]));

		$executor->executeQuery("", []);
	}

	public function test_NullMutationType_executeQuery_LoadsMutationType()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock
			->expects($this->once())
			->method('getMutationType')
			->willReturn(new ObjectType([ 'name' => 'Query' ]));

		$executor->executeQuery("", []);
	}

	public function test_ValidQueryType_executeQuery_HasNoError()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);
		$this->typeRegistryMock
			->method('getQueryType')
			->willReturn(new ObjectType([ 'name' => 'Query' ]));

		$retVal = $executor->executeQuery("", []);

		$this->assertFalse($retVal->hasError());
	}

	public function test_InvalidQueryType_executeQuery_HasEmptyResponseText()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$result = $executor->executeQuery("", []);

		$this->assertSame("", $result->getResponseText());
	}
}