<?php


namespace Kronos\Tests\GraphQLFramework\Executor;


use GraphQL\Type\Definition\ObjectType;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;
use Kronos\Mocks\Controllers\Exception\DummyClientException;
use Kronos\Mocks\Controllers\Exception\DummyServerException;
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

	public function test_InvalidQueryTypeNotDevMode_executeQuery_ContainsInternalErrorResponseText()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$result = $executor->executeQuery("", []);

		$this->assertContains("An internal error has occured", $result->getResponseText());
	}

	public function test_InvalidQueryTypeDevMode_executeQuery_ContainsInternalExceptionDetails()
	{
		$configuration = new FrameworkConfiguration();
		$configuration->enableDevMode();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$result = $executor->executeQuery("", []);

		$this->assertContains("\"internalException\"", $result->getResponseText());
	}

	public function test_ClientExceptionExceptionThrownDevModeOff_executeQuery_ContainsClientExceptionMessage()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyClientException()));

		$result = $executor->executeQuery("query { a { id } }", []);

		$this->assertContains(DummyClientException::MSG, $result->getResponseText());
	}

	public function test_ClientExceptionExceptionThrownDevModeOff_executeQuery_ContainsClientExceptionCode()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyClientException()));

		$result = $executor->executeQuery("query { a { id } }", []);

		$this->assertContains(DummyClientException::CODE, $result->getResponseText());
	}

	public function test_ClientExceptionExceptionThrownDevModeOff_executeQuery_ContainsClientExceptionStatusCode()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyClientException()));

		$result = $executor->executeQuery("query { a { id } }", []);

		$this->assertContains((string)DummyClientException::STATUS_CODE, $result->getResponseText());
	}

	public function test_ServerExceptionExceptionThrownDevModeOff_executeQuery_ContainsGenericInternalExceptionMessage()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyServerException()));

		$result = $executor->executeQuery("query { a { id } }", []);

		$this->assertContains("An internal error has occured", $result->getResponseText());
	}

	public function test_ServerExceptionExceptionThrownDevModeOff_executeQuery_DoesNotLeakExceptionData()
	{
		$configuration = new FrameworkConfiguration();
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyServerException()));

		$result = $executor->executeQuery("query { a { id } }", []);

		$this->assertNotContains("DummyServerException", $result->getResponseText());
		$this->assertNotContains("stack", $result->getResponseText());
		$this->assertNotContains("trace", $result->getResponseText());
		$this->assertNotContains("\.php", $result->getResponseText());
		$this->assertNotContains(" line", $result->getResponseText());
	}

	public function test_ExceptionHandlerConfigured_executeQuery_ExceptionHandlerIsCalled()
	{
		$configuration = new FrameworkConfiguration();
		$configuration->setExceptionHandler(function ($exception) {
			$this->assertTrue(true);
		});
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException(new DummyServerException()));

		$executor->executeQuery("query { a { id } }", []);
	}

	public function test_ExceptionHandlerConfigured_executeQuery_ExceptionHandlerContainsException()
	{
		$configuration = new FrameworkConfiguration();
		$thrownEx = new DummyServerException();
		$configuration->setExceptionHandler(function ($exception) use ($thrownEx) {
			$this->assertSame($thrownEx, $exception);
		});
		$executor = new Executor($configuration, $this->typeRegistryMock);

		$this->typeRegistryMock->method('getQueryType')->will($this->throwException($thrownEx));

		$executor->executeQuery("query { a { id } }", []);
	}
}
