<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint;


use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use HttpException;
use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Kronos\GraphQLFramework\EntryPoint\Http\HttpRequestDispatcher;
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
     * @var FrameworkConfiguration
     */
	protected $configuration;

    /**
     * @var HandledPayloadResult
     */
	protected $successfulDispatcherResult;

    /**
     * @var ExecutorResult
     */
	protected $successfulExecutorResult;

    /**
     * @var HttpRequestDispatcher|MockObject
     */
	protected $mockedDispatcher;

	/**
	 * @var Executor|MockObject
	 */
	protected $mockedExecutor;

    /**
     * @var ServerRequest
     */
    protected $dummyInputRequest;

    public function setUp()
	{
		parent::setUp();

		$this->configuration = new FrameworkConfiguration();

		$this->dummyInputRequest = new ServerRequest('GET', new Uri(''));

		$this->successfulDispatcherResult = new HandledPayloadResult("", []);
		$this->successfulExecutorResult = new ExecutorResult("{}");

		$this->mockedDispatcher = $this->getMockBuilder(HttpRequestDispatcher::class)
            ->disableOriginalConstructor()
            ->setMethods(['dispatch'])
            ->getMock();

		$this->mockedExecutor = $this->getMockBuilder(Executor::class)
			->disableOriginalConstructor()
			->setMethods(['configure', 'executeQuery'])
			->getMock();
	}

    /**
     * @return HttpEntryPoint
     */
	protected function getEntryPoint()
    {
        return new HttpEntryPoint($this->configuration, $this->mockedDispatcher, $this->mockedExecutor);
    }

    protected function givenDispatcherSucceed()
    {
        $this->mockedDispatcher->method('dispatch')->willReturn($this->successfulDispatcherResult);
    }

    protected function givenExecutorSucceed()
    {
        $this->mockedExecutor->method('executeQuery')->willReturn($this->successfulExecutorResult);
    }

    public function test_RequestSuccessful_executeRequest_RelaysQueryResponseToDispatcher()
    {
        $this->givenExecutorSucceed();

        $this->mockedDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->dummyInputRequest)
            ->willReturn($this->successfulDispatcherResult);

        $entryPoint = $this->getEntryPoint();

        $entryPoint->executeRequest($this->dummyInputRequest);
    }

    public function test_DispatchFailure_executeRequest_ReturnsResponseToClient()
    {
        $this->mockedDispatcher
            ->method('dispatch')
            ->willThrowException(new CannotHandleRequestException("PATCH"));

        $entryPoint = $this->getEntryPoint();

        $result = $entryPoint->executeRequest($this->dummyInputRequest);
        $this->assertInstanceOf(Response::class, $result);
    }

    public function test_RequestSuccessful_executeRequest_ConfiguresExecutor()
    {
        $this->givenDispatcherSucceed();
        $this->givenExecutorSucceed();

        $this->mockedExecutor->expects($this->once())->method('configure')->with($this->configuration);

        $entryPoint = $this->getEntryPoint();

        $entryPoint->executeRequest($this->dummyInputRequest);
    }

    public function test_RequestSuccessful_executeRequest_ExecutesQueryWithGivenDispatcherData()
    {
        $this->givenDispatcherSucceed();
        $this->givenExecutorSucceed();

        $this->mockedExecutor->expects($this->once())->method('executeQuery')->with(
            $this->successfulDispatcherResult->getQuery(),
            $this->successfulDispatcherResult->getVariables()
        );

        $entryPoint = $this->getEntryPoint();

        $entryPoint->executeRequest($this->dummyInputRequest);
    }

    public function test_RequestSuccessful_executeRequest_Returns200StatusCode()
    {
        $this->givenDispatcherSucceed();
        $this->givenExecutorSucceed();

        $entryPoint = $this->getEntryPoint();

        $this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}"));

        $response = $entryPoint->executeRequest($this->dummyInputRequest);

        $this->assertSame(200, $response->getStatusCode());
    }

	public function test_RequestThrowsClientException_executeRequest_ReturnsCorrectStatusCode()
	{
        $this->givenDispatcherSucceed();

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}", new DummyClientException()));

        $entryPoint = $this->getEntryPoint();

		$response = $entryPoint->executeRequest($this->dummyInputRequest);

		$this->assertSame(DummyClientException::STATUS_CODE, $response->getStatusCode());
	}

	public function test_RequestThrowsServerException_executeRequest_Returns500StatusCode()
	{
        $this->givenDispatcherSucceed();

		$this->mockedExecutor->method('executeQuery')->willReturn(new ExecutorResult("{}", new DummyServerException()));

        $entryPoint = $this->getEntryPoint();

		$response = $entryPoint->executeRequest($this->dummyInputRequest);

		$this->assertSame(500, $response->getStatusCode());
	}
}
