<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint\Http;


use GuzzleHttp\Psr7\ServerRequest;
use function GuzzleHttp\Psr7\stream_for;
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

    /**
     * @var PostRequestHandler
     */
    protected $requestHandler;

    protected function setUp()
    {
        $this->requestHandler = new PostRequestHandler();

        $baseRequest = new ServerRequest('POST', new Uri(''));
        $this->withVarsPostRequest = $baseRequest->withBody(stream_for(self::REQ_QUERY_WITH_VARS));

        $this->malformedVarsPostRequest = $baseRequest->withBody(stream_for(self::REQ_MALFORMED));

        $this->noQueryPostRequest = $baseRequest->withBody(stream_for(self::REQ_NO_QUERY));
        $this->noVarsPostRequest = $baseRequest->withBody(stream_for(self::REQ_QUERY));

        $this->getRequest = new ServerRequest('GET', new Uri(''));
    }

    public function test_WithVarsPostRequest_canHandle_ReturnsTrue()
    {
        $this->assertTrue($this->requestHandler->canHandle($this->withVarsPostRequest));
    }

    public function test_GetRequest_canHandle_ReturnsFalse()
    {
        $this->assertFalse($this->requestHandler->canHandle($this->getRequest));
    }

    public function test_WithVarsPostRequest_handle_ReturnsHandledPayloadResult()
    {
        $actual = $this->requestHandler->handle($this->withVarsPostRequest);

        $this->assertInstanceOf(HandledPayloadResult::class, $actual);
    }

    public function test_WithVarsPostRequest_handle_ResultContainsExpectedQueryData()
    {
        $actual = $this->requestHandler->handle($this->withVarsPostRequest);

        $this->assertSame(self::REQ_QUERY_PARSED, $actual->getQuery());
    }

    public function test_WithVarsPostRequest_handle_ResultContainsExpectedVariableData()
    {
        $actual = $this->requestHandler->handle($this->withVarsPostRequest);

        $this->assertEquals(self::REQ_VARS_PARSED, $actual->getVariables());
    }

    public function test_NoVarsPostRequest_handle_ResultContainsExpectedQueryData()
    {
        $actual = $this->requestHandler->handle($this->noVarsPostRequest);

        $this->assertSame(self::REQ_QUERY_PARSED, $actual->getQuery());
    }


    public function test_NoVarsPostRequest_handle_ResultVariablesIsEmptyArray()
    {
        $actual = $this->requestHandler->handle($this->noVarsPostRequest);

        $this->assertSame([], $actual->getVariables());
    }

    public function test_NoQueryPostRequest_handle_ThrowsHttpQueryRequiredException()
    {
        $this->expectException(HttpQueryRequiredException::class);

        $this->requestHandler->handle($this->noQueryPostRequest);
    }

    public function test_GetRequest_handle_ThrowsCannotHandleRequestException()
    {
        $this->expectException(CannotHandleRequestException::class);

        $this->requestHandler->handle($this->getRequest);
    }

    public function test_MalformedVarsPostRequest_handle_ThrowsMalformedRequestException()
    {
        $this->expectException(MalformedRequestException::class);

        $this->requestHandler->handle($this->malformedVarsPostRequest);
    }
}