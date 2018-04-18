<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint;


use GuzzleHttp\Psr7\ServerRequest;
use function GuzzleHttp\Psr7\stream_for;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\HttpEntryPoint;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\Executor\ExecutorResult;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\Tests\GraphQLFramework\HttpAwareTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));
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

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$request = $this->getRequest()
			->withQueryParams(['query' => "{\n    id\n}"]);

		$entryPoint->executeRequest($request);
	}

	public function test_GetRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

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

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = $this->postRequest()
			->withBody(stream_for(json_encode(['query' => "query {\n    id\n}"])));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestWithVariables_executeRequest_VariablesArePassedToExecutor()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

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

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_GET);

		$serverRequest = $this->getRequest();

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostRequestNoQuery_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = $this->postRequest();

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_PostInvalidJSON_executeRequest_ThrowsHttpQueryRequiredException()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->expectException(HttpQueryRequiredException::class);
		$this->expectExceptionMessage(HttpQueryRequiredException::MSG_POST);

		$serverRequest = $this->postRequest()
			->withBody(stream_for('aaa'));

		$entryPoint->executeRequest($serverRequest);
	}

	public function test_GetRequestWithAlreadyQueryPrefix_executeRequest_DoesNotDuplicateQueryPrefix()
	{
		$entryPoint = $this->getExecutorMockedEntryPoint();

		$this->mockedExecutor->expects($this->once())->method('executeQuery')->with("query {\n    id\n}", []);

		$serverRequest = $this->getRequest()
			->withQueryParams(['query' => "query {\n    id\n}"]);

		$entryPoint->executeRequest($serverRequest);
	}
}