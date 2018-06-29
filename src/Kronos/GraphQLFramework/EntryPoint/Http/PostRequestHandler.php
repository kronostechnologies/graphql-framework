<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\MalformedRequestException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Psr\Http\Message\ServerRequestInterface;

class PostRequestHandler implements HttpRequestHandlerInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * GetRequestHandler constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return HandledPayloadResult
     * @throws MalformedRequestException
     * @throws CannotHandleRequestException
     * @throws HttpQueryRequiredException
     */
    public function handle()
    {
        if (!$this->canHandle()) {
            throw new CannotHandleRequestException($this->request->getMethod());
        }

        $parsedBody = $this->getParsedBodyAsJson();

        $queryText = $this->getQueryText($parsedBody);
        $variables = $this->getVariablesArray($parsedBody);

        return new HandledPayloadResult($queryText, $variables);
    }

    /**
     * @param $args
     * @return mixed
     * @throws HttpQueryRequiredException
     */
    protected function getQueryText($args)
    {
        if (!array_key_exists('query', $args)) {
            throw new HttpQueryRequiredException('POST');
        }

        $query = $args['query'];
        return $query;
    }

    /**
     * @param $args
     * @return array|mixed
     */
    protected function getVariablesArray($args)
    {
        $variables = [];

        if (array_key_exists('variables', $args)) {
            $variables = $args['variables'];
        }
        return $variables;
    }

    /**
     * @return bool
     */
    public function canHandle()
    {
        return $this->request->getMethod() === 'POST';
    }

    /**
     * @return mixed
     * @throws MalformedRequestException
     */
    protected function getParsedBodyAsJson()
    {
        $parsedBody = @json_decode($this->request->getParsedBody(), true);
        if (!is_array($parsedBody)) {
            throw new MalformedRequestException("query");
        }
        return $parsedBody;
    }
}