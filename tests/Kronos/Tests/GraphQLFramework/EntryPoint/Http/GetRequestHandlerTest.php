<?php


namespace Kronos\Tests\GraphQLFramework\EntryPoint\Http;


use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\MalformedRequestException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Kronos\GraphQLFramework\EntryPoint\Http\GetRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestHandlerTest extends TestCase
{
    const REQ_QUERY = 'query { test { id } }';
    const REQ_VARS = '{"a":1,"b":2}';
    const REQ_VARS_PARSED = ['a' => 1, 'b' => 2];

    const MALFORMED_VARS_JSON = 'aabbbsdfkm--';

    /**
     * @var ServerRequestInterface
     */
    protected $malformedVarsGetRequest;

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

    /**
     * @var GetRequestHandler
     */
    protected $requestHandler;

    protected function setUp()
    {
        $this->requestHandler = new GetRequestHandler();

        $baseRequest = new ServerRequest('GET', new Uri(''));
        $this->withVarsGetRequest = $baseRequest->withQueryParams([
            'query' => self::REQ_QUERY,
            'variables' => self::REQ_VARS,
        ]);

        $this->malformedVarsGetRequest = $baseRequest->withQueryParams([
            'query' => self::REQ_QUERY,
            'variables' => self::MALFORMED_VARS_JSON,
        ]);

        $this->noQueryGetRequest = clone $baseRequest;
        $this->noVarsGetRequest = $baseRequest->withQueryParams([
            'query' => self::REQ_QUERY,
        ]);

        $this->postRequest = new ServerRequest('POST', new Uri(''));
    }

    public function test_WithVarsGetRequest_canHandle_ReturnsTrue()
    {
        $this->assertTrue($this->requestHandler->canHandle($this->withVarsGetRequest));
    }

    public function test_PostRequest_canHandle_ReturnsFalse()
    {
        $this->assertFalse($this->requestHandler->canHandle($this->postRequest));
    }

    public function test_WithVarsGetRequest_handle_ReturnsHandledPayloadResult()
    {
        $actual = $this->requestHandler->handle($this->withVarsGetRequest);

        $this->assertInstanceOf(HandledPayloadResult::class, $actual);
    }

    public function test_WithVarsGetRequest_handle_ResultContainsExpectedQueryData()
    {
        $actual = $this->requestHandler->handle($this->withVarsGetRequest);

        $this->assertSame(self::REQ_QUERY, $actual->getQuery());
    }

    public function test_WithVarsGetRequest_handle_ResultContainsExpectedVariableData()
    {
        $actual = $this->requestHandler->handle($this->withVarsGetRequest);

        $this->assertEquals(self::REQ_VARS_PARSED, $actual->getVariables());
    }

    public function test_NoVarsGetRequest_handle_ResultContainsExpectedQueryData()
    {
        $actual = $this->requestHandler->handle($this->noVarsGetRequest);

        $this->assertSame(self::REQ_QUERY, $actual->getQuery());
    }

    public function test_NoVarsGetRequest_handle_ResultVariablesIsEmptyArray()
    {
        $actual = $this->requestHandler->handle($this->noVarsGetRequest);

        $this->assertSame([], $actual->getVariables());
    }

    public function test_NoQueryGetRequest_handle_ThrowsHttpQueryRequiredException()
    {
        $this->expectException(HttpQueryRequiredException::class);

        $this->requestHandler->handle($this->noQueryGetRequest);
    }

    public function test_PostRequest_handle_ThrowsCannotHandleRequestException()
    {
        $this->expectException(CannotHandleRequestException::class);

        $this->requestHandler->handle($this->postRequest);
    }

    public function test_MalformedVarsGetRequest_handle_ThrowsMalformedRequestException()
    {
        $this->expectException(MalformedRequestException::class);

        $this->requestHandler->handle($this->malformedVarsGetRequest);
    }
}