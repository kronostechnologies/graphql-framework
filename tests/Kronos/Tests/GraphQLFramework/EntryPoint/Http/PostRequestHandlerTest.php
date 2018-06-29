<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint\Http;


use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\MalformedRequestException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Kronos\GraphQLFramework\EntryPoint\Http\PostRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PostRequestHandlerTest extends TestCase
{
    const REQ_QUERY = '{"query":"query { test { id } }"}';
    const REQ_QUERY_WITH_VARS = '{"query":"query { test { id } }","variables":{"a":1,"b":2}}';
    const REQ_QUERY_PARSED = 'query { test { id } }';
    const REQ_VARS_PARSED = ['a' => 1, 'b' => 2];

    const REQ_NO_QUERY = '{}';
    const REQ_MALFORMED = '...sdfsd';

    /**
     * @var ServerRequestInterface
     */
    protected $malformedVarsPostRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $noQueryPostRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $noVarsPostRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $getRequest;

    /**
     * @var ServerRequestInterface
     */
    protected $withVarsPostRequest;

    protected function setUp()
    {
        $baseRequest = new ServerRequest('POST', new Uri(''));
        $this->withVarsPostRequest = $baseRequest->withParsedBody(self::REQ_QUERY_WITH_VARS);

        $this->malformedVarsPostRequest = $baseRequest->withParsedBody(self::REQ_MALFORMED);

        $this->noQueryPostRequest = $baseRequest->withParsedBody(self::REQ_NO_QUERY);
        $this->noVarsPostRequest = $baseRequest->withParsedBody(self::REQ_QUERY);

        $this->getRequest = new ServerRequest('GET', new Uri(''));
    }

    public function test_WithVarsPostRequest_canHandle_ReturnsTrue()
    {
        $handler = new PostRequestHandler($this->withVarsPostRequest);

        $this->assertTrue($handler->canHandle());
    }

    public function test_GetRequest_canHandle_ReturnsFalse()
    {
        $handler = new PostRequestHandler($this->getRequest);

        $this->assertFalse($handler->canHandle());
    }

    public function test_WithVarsPostRequest_handle_ReturnsHandledPayloadResult()
    {
        $handler = new PostRequestHandler($this->withVarsPostRequest);

        $actual = $handler->handle();

        $this->assertInstanceOf(HandledPayloadResult::class, $actual);
    }

    public function test_WithVarsPostRequest_handle_ResultContainsExpectedQueryData()
    {
        $handler = new PostRequestHandler($this->withVarsPostRequest);

        $actual = $handler->handle();

        $this->assertSame(self::REQ_QUERY_PARSED, $actual->getQuery());
    }

    public function test_WithVarsPostRequest_handle_ResultContainsExpectedVariableData()
    {
        $handler = new PostRequestHandler($this->withVarsPostRequest);

        $actual = $handler->handle();

        $this->assertEquals(self::REQ_VARS_PARSED, $actual->getVariables());
    }

    public function test_NoVarsPostRequest_handle_ResultContainsExpectedQueryData()
    {
        $handler = new PostRequestHandler($this->noVarsPostRequest);

        $actual = $handler->handle();

        $this->assertSame(self::REQ_QUERY_PARSED, $actual->getQuery());
    }


    public function test_NoVarsPostRequest_handle_ResultVariablesIsEmptyArray()
    {
        $handler = new PostRequestHandler($this->noVarsPostRequest);

        $actual = $handler->handle();

        $this->assertSame([], $actual->getVariables());
    }

    public function test_NoQueryPostRequest_handle_ThrowsHttpQueryRequiredException()
    {
        $this->expectException(HttpQueryRequiredException::class);

        $handler = new PostRequestHandler($this->noQueryPostRequest);
        $handler->handle();
    }

    public function test_GetRequest_handle_ThrowsCannotHandleRequestException()
    {
        $this->expectException(CannotHandleRequestException::class);

        $handler = new PostRequestHandler($this->getRequest);
        $handler->handle();
    }

    public function test_MalformedVarsPostRequest_handle_ThrowsMalformedRequestException()
    {
        $this->expectException(MalformedRequestException::class);

        $handler = new PostRequestHandler($this->malformedVarsPostRequest);
        $handler->handle();
    }
}