<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint;


use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\HttpEntryPoint;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\Executor\ExecutorResult;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\Mocks\Controllers\Exception\DummyClientException;
use Kronos\Mocks\Controllers\Exception\DummyServerException;
use Kronos\Tests\GraphQLFramework\HttpAwareTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\Psr7\stream_for;

class HttpEntryPointTest extends TestCase
{
	use HttpAwareTestTrait;

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
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$request = $this->getRequest()
			->withQueryParams(['query' => "{\n    id\n}"]);

		$entryPoint->executeRequest($request);
	}

	public function test_GetRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with(
			"query {\n    id\n}",
			['avar' => '111']
		);

		$serverRequest = $this->getRequest()
			->withQueryParams([
				'query' => "{\n    id\n}",
				'variables' => '{ "avar": "111" }'
			]);

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestWithBodyNoVars_executeRequest_PassesQueryEmptyArrayToExecutor()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = $this->postRequest()
			->withBody(stream_for(json_encode(['query' => "query {\n    id\n}"])));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with(
			"query {\n    id\n}",
			['avar' => '111']
		);

		$serverRequest = $this->postRequest()
			->withBody(stream_for(json_encode(['query' => "query {\n    id\n}", 'variables' => '{ "avar": "111" }'])));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestNoQuery_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_GET);

		$serverRequest = $this->getRequest();

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestNoQuery_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = $this->postRequest();

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostInvalidJSON_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = $this->postRequest()
			->withBody(stream_for('aaa'));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestWithAlreadyQueryPrefix_executeRequest_DoesNotDuplicateQueryPrefix()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();
		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = $this->getRequest()
			->withQueryParams(['query' => "query {\n    id\n}"]);

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_RequestThrowsClientException_executeRequest_ReturnsCorrectStatusCode()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}", new DummyClientException()));

		$serverRequest = $this->getRequest()
			->withQueryParams(['query' => "query {\n    id\n}"]);

		$response = $entryPoint->executeRequest($serverRequest);

		$this->assertSame(DummyClientException::STATUS_CODE, $response->getStatusCode());
	}

	public function test_CorrectRequest_executeRequest_Returns200StatusCode()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

		$serverRequest = $this->getRequest()
			->withQueryParams(['query' => "query {\n    id\n}"]);

		$response = $entryPoint->executeRequest($serverRequest);

		$this->assertSame(200, $response->getStatusCode());
	}

	public function test_RequestThrowsServerException_executeRequest_Returns500StatusCode()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}", new DummyServerException()));

		$serverRequest = $this->getRequest()
			->withQueryParams(['query' => "query {\n    id\n}"]);

		$response = $entryPoint->executeRequest($serverRequest);

		$this->assertSame(500, $response->getStatusCode());
	}
}
