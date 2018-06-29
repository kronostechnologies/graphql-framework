<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint\Http;


use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Kronos\GraphQLFramework\EntryPoint\Http\GetRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestHandlerTest extends TestCase
{
    const REQ_QUERY = 'query { test { id } }';
    const REQ_VARS = '{"a":1,"b":2}';
    const REQ_VARS_PARSED = ['a' => 1, 'b' => 2];

    /**
     * @var ServerRequestInterface
     */
    protected $noQueryGetRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $noVarsGetRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $postRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $withVarsGetRequest;

    protected function setUp()
    {
        $baseRequest = new ServerRequest('GET', new Uri(''));
        $this->withVarsGetRequest = $baseRequest->withQueryParams([
            'query' => self::REQ_QUERY,
            'variables' => self::REQ_VARS,
        ]);

        $this->noQueryGetRequest = clone $baseRequest;
        $this->noVarsGetRequest = $baseRequest->withQueryParams([
            'query' => self::REQ_QUERY,
        ]);

        $this->postRequest = new ServerRequest('POST', new Uri(''));
    }

    public function test_WithVarsGetRequest_canHandle_ReturnsTrue()
    {
        $handler = new GetRequestHandler($this->withVarsGetRequest);

        $this->assertTrue($handler->canHandle());
    }

    public function test_PostRequest_canHandle_ReturnsFalse()
    {
        $handler = new GetRequestHandler($this->postRequest);

        $this->assertFalse($handler->canHandle());
    }

    public function test_WithVarsGetRequest_handle_ReturnsHandledPayloadResult()
    {
        $handler = new GetRequestHandler($this->withVarsGetRequest);

        $actual = $handler->handle();

        $this->assertInstanceOf(HandledPayloadResult::class, $actual);
    }

    public function test_WithVarsGetRequest_handle_ResultContainsExpectedQueryData()
    {
        $handler = new GetRequestHandler($this->withVarsGetRequest);

        $actual = $handler->handle();

        $this->assertSame(self::REQ_QUERY, $actual->getQuery());
    }

    public function test_WithVarsGetRequest_handle_ResultContainsExpectedVariableData()
    {
        $handler = new GetRequestHandler($this->withVarsGetRequest);

        $actual = $handler->handle();

        $this->assertEquals(self::REQ_VARS_PARSED, $actual->getVariables());
    }

    public function test_NoVarsGetRequest_handle_ResultContainsExpectedQueryData()
    {
        $handler = new GetRequestHandler($this->noVarsGetRequest);

        $actual = $handler->handle();

        $this->assertSame(self::REQ_QUERY, $actual->getQuery());
    }

    public function test_NoVarsGetRequest_handle_ResultVariablesIsEmptyArray()
    {
        $handler = new GetRequestHandler($this->noVarsGetRequest);

        $actual = $handler->handle();

        $this->assertSame([], $actual->getVariables());
    }

    public function test_NoQueryGetRequest_handle_ThrowsHttpQueryRequiredException()
    {
        $this->expectException(HttpQueryRequiredException::class);

        $handler = new GetRequestHandler($this->noQueryGetRequest);
        $handler->handle();
    }

    public function test_PostRequest_handle_ThrowsCannotHandleRequestException()
    {
        $this->expectException(CannotHandleRequestException::class);

        $handler = new GetRequestHandler($this->noQueryGetRequest);
        $handler->handle();
    }
}