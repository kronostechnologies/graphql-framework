<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint;


use GraphQL\GraphQL;
use Grpc\Server;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\HttpEntryPoint;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\Executor\ExecutorResult;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HttpEntryPointTest extends TestCase
{
	/**
	 * @var Executor|MockObject
	 */
	protected $mockedExecutor;

	public function setUp()
	{
		parent::setUp();

		$this->mockedExecutor = $this->getMockBuilder(Executor::class)
			->disableOriginalConstructor()
			->setMethods(['executeQuery'])
			->getMock();
	}

	/**
	 * @return HttpEntryPoint|MockObject
	 */
	protected function getExecutorMockedEntryPoint()
	{
		$mock = $this->getMockBuilder(HttpEntryPoint::class)
			->setMethods(['getExecutor'])
			->setConstructorArgs([new FrameworkConfiguration()])
			->getMock();
		$mock->method('getExecutor')->willReturn($this->mockedExecutor);

		return $mock;
	}

	public function test_GetRequestWithQueryNoVars_executeRequest_PassesQueryEmptyArrayToExecutor()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = new ServerRequest('GET', 'http://127.0.0.1/');
		$serverRequest = $serverRequest->withQueryParams(['query' => "{\n    id\n}"]);

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with(
			"query {\n    id\n}",
			['avar' => '111']
		);

		$serverRequest = new ServerRequest('GET', 'http://127.0.0.1/');
		$serverRequest = $serverRequest->withQueryParams(['query' => "{\n    id\n}", 'variables' => '{ "avar": "111" }']);

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestWithBodyNoVars_executeRequest_PassesQueryEmptyArrayToExecutor()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = new ServerRequest('POST', 'http://127.0.0.1/', [
			'Content-Type' => 'application/json'
		], json_encode(['query' => "query {\n    id\n}"]));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with(
			"query {\n    id\n}",
			['avar' => '111']
		);

		$serverRequest = new ServerRequest('POST', 'http://127.0.0.1/', [
			'Content-Type' => 'application/json'
		], json_encode(['query' => "query {\n    id\n}", 'variables' => '{ "avar": "111" }']));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestNoQuery_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_GET);

		$serverRequest = new ServerRequest('GET', 'http://127.0.0.1/');

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestNoQuery_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = new ServerRequest('POST', 'http://127.0.0.1/', [
			'Content-Type' => 'application/json'
		], json_encode([]));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostEmptyBody_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = new ServerRequest('POST', 'http://127.0.0.1/', [
			'Content-Type' => 'application/json'
		]);

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostInvalidJSON_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = new ServerRequest('POST', 'http://127.0.0.1/', [
			'Content-Type' => 'application/json'
		], 'aslfmsd');

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestWithAlreadyQueryPrefix_executeRequest_DoesNotDuplicateQueryPrefix()
	{
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = new ServerRequest('GET', 'http://127.0.0.1/');
		$serverRequest = $serverRequest->withQueryParams(['query' => "query {\n    id\n}"]);

		$entryPoint->executeRequest($serverRequest);
	}
}